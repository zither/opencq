<?php
namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use Xian\Object\PlayerParty;
use Xian\Object\PlayerPartyMember;
use function player\getplayer;
use function player\getplayer1;
use function player\getPlayerById;

class Party extends AbstractHandler
{
    public function showPartyMembers()
    {
        $player = getPlayerById($this->db(), $this->uid(), true);
        $data = [
            'is_leader' => false,
            'player' => $player,
        ];
        if ($player->partyId) {
            $party = PlayerParty::get($this->db(), $player->partyId);
            if ($party->id) {
                $data['is_leader'] = $party->uid == $player->id;
                $members = $this->db()->select('player_party_member', ['[>]game1' => ['uid' => 'id']], [
                    'player_party_member.id',
                    'player_party_member.party_id',
                    'player_party_member.uid',
                    'player_party_member.status',
                    'game1.name',
                    'game1.vip',
                ], [
                    'player_party_member.party_id' => $party->id,
                    'ORDER' => ['player_party_member.status' => 'DESC']
                ]);
                $data['members'] = [];
                $status = ['申请中', '自由', '跟随'];
                foreach ($members as $v) {
                    $v['name'] = Helper::getVipName($v);
                    $v['status_text'] = $status[$v['status']];
                    $data['members'][] = PlayerPartyMember::fromArray($v);
                }
            }
            $data['party'] = $party;
        }

        $this->display('party/member', $data);
    }

    public function showParties()
    {
        $player = getPlayerById($this->db(), $this->uid(), true);
        $parties = $this->db()->select('player_party', '*');
        $leadersMap = [];
        $leaderIds = [];
        foreach ($parties as $v) {
            $leaderIds[] = $v['uid'];
        }
        if (!empty($leaderIds)) {
            $leaders = $this->db()->select('game1', ['id', 'name', 'vip'], ['id' => $leaderIds]);
            foreach ($leaders as $v) {
                $leadersMap[$v['id']] = $v;
            }
        }
        $data = [
            'parties' => [],
            'player' => $player
        ];
        foreach ($parties as $v) {
            $v['leader_name'] = Helper::getVipName($leadersMap[$v['uid']]);
            $data['parties'][] = PlayerParty::fromArray($v);
        }

        $this->display('party/party', $data);
    }

    public function showCreationForm()
    {
        $this->display('party/creation_form');
    }

    public function createParty()
    {
        $name = Helper::filterVar($this->postParam('name'), 'STRING');
        if (mb_strlen($name) < 2 || mb_strlen($name) > 15) {
            $this->flash->error("名称长度必须在2-6个之间");
            $this->doRawCmd($this->lastAction());
        }
        if (preg_match('/[^\x{4e00}-\x{9fa5}]/u',$name)) {
            $this->flash->error("队伍名称只能使用中文");
            $this->doRawCmd($this->lastAction());
        }
        $player = getPlayerById($this->db(), $this->uid(), true);
        if ($player->partyId) {
            $this->flash->error("你已在队伍中，无法创建");
            $this->doRawCmd('cmd=party-member');
        }
        $partyId = PlayerParty::add($this->db(), ['name' => $name, 'uid' => $player->id, 'is_closed' => 0]);
        PlayerPartyMember::add($this->db(), [
            'party_id' => $partyId,
            'uid' => $player->id,
            'name' => $player->name,
            'status' => 2, //跟随状态
            'is_leader' => 1 // 队长
        ]);
        $this->db()->update('game1', ['party_id' => $partyId], ['id' => $player->id]);
        $this->flash->success("队伍创建成功");
        $this->doRawCmd('cmd=party-member');
    }

    public function joinParty()
    {
        $partyId = $this->params['party_id'] ?? 0;
        if (!$partyId) {
            $this->flash->error("无效队伍，申请失败");
            $this->doRawCmd($this->lastAction());
        }
        $playerParty = PlayerParty::get($this->db(), $partyId);
        if (!$playerParty->id) {
            $this->flash->error("无效队伍，申请失败");
            $this->doRawCmd($this->lastAction());
        }
        if ($playerParty->isClosed) {
            $this->flash->error("队伍已关闭入队申请，申请失败");
            $this->doRawCmd($this->lastAction());
        }
        $player = getPlayerById($this->db(), $this->uid(), true);
        if ($player->partyId) {
            $this->flash->error("你已在队伍中，无法申请");
            $this->doRawCmd('cmd=party-member');
        }
        $exists = $this->db()->count('player_party_member', [
            'party_id' => $playerParty->id,
            'uid' => $player->id
        ]);
        if ($exists) {
            $this->flash->error("请勿重复发起申请");
            $this->doRawCmd($this->lastAction());
        }
        PlayerPartyMember::add($this->db(), [
            'party_id' => $playerParty->id,
            'uid' => $player->id,
            'name' => $player->name,
            'status' => 0,
            'is_leader' => 0,
        ]);
        $vipName = Helper::getVipName($player);
        $this->db()->insert('im', [
            'uid' => 0,
            'tid' => $playerParty->id,
            'type' => 3,
            'content' => "玩家{$vipName}发起了入队申请",
        ]);
        $this->flash->success("申请发送成功，请等待队长审核");
        $this->doRawCmd($this->lastAction());
    }

    public function allowPartyMember()
    {
        $id = $this->params['member_id'] ?? 0;
        if (!$id) {
            $this->flash->error("无效申请");
            $this->doRawCmd($this->lastAction());
        }
        $player = getPlayerById($this->db(), $this->uid(), true);
        if (!$player->partyId) {
            $this->flash->error("你没有队伍");
            $this->doRawCmd($this->lastAction());
        }
        $playerParty = PlayerParty::get($this->db(), $player->partyId);
        $member = PlayerPartyMember::get($this->db(), $id);
        if (!$playerParty->id || !$member->id || $playerParty->id != $member->partyId) {
            $this->flash->error("非法申请");
            $this->doRawCmd($this->lastAction());
        }
        if ($player->id != $playerParty->uid) {
            $this->flash->error("你不是队长，操作失败");
            $this->doRawCmd($this->lastAction());
        }
        $other = getplayer1($member->uid, $this->db());
        if ($other->partyId) {
            PlayerPartyMember::delete($this->db(), ['id' => $member->id]);
            $this->flash->error("目标已加入其他队伍，申请已删除");
            $this->doRawCmd($this->lastAction());
        }
        $count = $this->db()->count('player_party_member', ['party_id' => $playerParty->id, 'status[>]' => 0]);
        if ($count >= 5) {
            $this->flash->error("队伍成员已达上限");
            $this->doRawCmd($this->lastAction());
        }
        // 通过审核
        PlayerPartyMember::update($this->db(), $member->id, ['status' => 1]);
        $name = Helper::getVipName($other);
        $this->db()->update('game1', ['party_id' => $playerParty->id], ['id' => $other->id]);
        $this->flash->success("操作成功，{$name}已加入队伍");

        $this->db()->insert('im', [
            'uid' => 0,
            'tid' => $playerParty->id,
            'type' => 3,
            'content' => "玩家{$name}通过申请，成功入队！",
        ]);

        $this->doRawCmd($this->lastAction());
    }

    public function rejectPartyMember()
    {
        $id = $this->params['member_id'] ?? 0;
        if (!$id) {
            $this->flash->error("无效申请");
            $this->doRawCmd($this->lastAction());
        }
        $player = getPlayerById($this->db(), $this->uid(), true);
        if (!$player->partyId) {
            $this->flash->error("你没有队伍");
            $this->doRawCmd($this->lastAction());
        }
        $playerParty = PlayerParty::get($this->db(), $player->partyId);
        $member = PlayerPartyMember::get($this->db(), $id);
        if (!$playerParty->id || !$member->id || $playerParty->id != $member->partyId) {
            $this->flash->error("非法申请");
            $this->doRawCmd($this->lastAction());
        }
        if ($player->id != $playerParty->uid) {
            $this->flash->error("你不是队长，操作失败");
            $this->doRawCmd($this->lastAction());
        }
        // 拒绝请求
        PlayerPartyMember::delete($this->db(), ['id' => $id, 'status' => 0]);
        $this->flash->success("操作成功，申请已删除");
        $this->doRawCmd($this->lastAction());
    }

    public function removePartyMember()
    {
        $id = $this->params['member_id'] ?? 0;
        if (!$id) {
            $this->flash->error("无效队员");
            $this->doRawCmd($this->lastAction());
        }
        $player = getPlayerById($this->db(), $this->uid(), true);
        if (!$player->partyId) {
            $this->flash->error("你没有队伍");
            $this->doRawCmd($this->lastAction());
        }
        $playerParty = PlayerParty::get($this->db(), $player->partyId);
        $member = PlayerPartyMember::get($this->db(), $id);
        if (!$playerParty->id || !$member->id || $playerParty->id != $member->partyId) {
            $this->flash->error("非法请求");
            $this->doRawCmd($this->lastAction());
        }
        if ($player->id != $playerParty->uid) {
            $this->flash->error("你不是队长，操作失败");
            $this->doRawCmd($this->lastAction());
        }
        $other = getplayer1($member->uid, $this->db());
        PlayerPartyMember::delete($this->db(), ['id' => $member->id]);
        if ($other->partyId == $member->partyId) {
            $this->db()->update('game1', ['party_id' => 0], ['id' => $other->id]);
        }
        $name = Helper::getVipName($other);
        $this->flash->success("操作成功，{$name}已移除队伍");

        $this->db()->insert('im', [
            'uid' => 0,
            'tid' => $playerParty->id,
            'type' => 3,
            'content' => "玩家{$name}被队长移除队伍！",
        ]);

        $this->doRawCmd($this->lastAction());
    }

    public function leaveParty()
    {
        $player = getplayer1($this->uid(), $this->db());
        if ($player->partyId) {
            $this->db()->update('game1', ['party_id' => 0], ['id' => $this->uid()]);
            PlayerPartyMember::delete($this->db(), ['party_id' => $player->partyId, 'uid' => $player->id]);
            $name = Helper::getVipName($player);
            $this->db()->insert('im', [
                'uid' => 0,
                'tid' => $player->partyId,
                'type' => 3,
                'content' => "玩家{$name}已退出队伍！",
            ]);
        }
        $this->flash->success("操作成功，你已退出队伍");
        $this->doRawCmd($this->lastAction());
    }

    public function followLeader()
    {
        $player = getplayer1($this->uid(), $this->db());
        if (!$player->partyId) {
            $this->flash->error("你没有队伍");
            $this->doRawCmd($this->lastAction());
        }
        $party = PlayerParty::get($this->db(), $player->partyId);
        if ($party->uid && $party->uid != $this->uid()) {
            $leader = getplayer1($party->uid, $this->db());
            if ($leader->nowmid != $player->nowmid) {
                $this->flash->error("你和队长不在同一个位置，无法跟随");
                $this->doRawCmd($this->lastAction());
            }
            $this->db()->update('player_party_member', ['status' => 2], [
                'uid' => $player->id,
                'party_id' => $party->id
            ]);
            $name = Helper::getVipName($player);
            $this->db()->insert('im', [
                'uid' => 0,
                'tid' => $player->partyId,
                'type' => 3,
                'content' => "玩家{$name}切换至组队跟随模式！",
            ]);
        }
        $this->flash->success("跟随成功，你将无法移动和战斗");
        $this->doRawCmd($this->lastAction());
    }

    public function unfollowLeader()
    {
        $player = getplayer1($this->uid(), $this->db());
        if (!$player->partyId) {
            $this->flash->error("你没有队伍");
            $this->doRawCmd($this->lastAction());
        }
        $party = PlayerParty::get($this->db(), $player->partyId);
        if ($party->uid && $party->uid != $this->uid()) {
            $this->db()->update('player_party_member', ['status' => 1], [
                'uid' => $player->id,
                'party_id' => $party->id
            ]);
            $name = Helper::getVipName($player);
            $this->db()->insert('im', [
                'uid' => 0,
                'tid' => $player->partyId,
                'type' => 3,
                'content' => "玩家{$name}取消组队跟随模式！",
            ]);
        }
        $this->flash->success("取消跟随成功，你现在可以自由移动和战斗");
        $this->doRawCmd($this->lastAction());
    }

    public function togglePartyRequest()
    {
        $partyId = $this->params['party_id'] ?? 0;
        if (!$partyId) {
            $this->flash->error('无效队伍');
            $this->doRawCmd($this->lastAction());
        }
        $player = getPlayerById($this->db(), $this->uid(), true);
        if (!$player->partyId) {
            $this->flash->error("你没有队伍");
            $this->doRawCmd($this->lastAction());
        }
        $playerParty = PlayerParty::get($this->db(), $player->partyId);
        if ($player->id != $playerParty->uid) {
            $this->flash->error("你不是队长，操作失败");
            $this->doRawCmd($this->lastAction());
        }
        PlayerParty::update($this->db(), $partyId, ['is_closed' => $playerParty->isClosed ? 0 : 1]);
        $this->flash->success("操作成功");
        $this->doRawCmd($this->lastAction());
    }

    public function deleteParty()
    {
        $partyId = $this->params['party_id'] ?? 0;
        if (!$partyId) {
            $this->flash->error('无效队伍');
            $this->doRawCmd($this->lastAction());
        }
        $player = getPlayerById($this->db(), $this->uid(), true);
        if (!$player->partyId) {
            $this->flash->error("你没有队伍");
            $this->doRawCmd($this->lastAction());
        }
        $playerParty = PlayerParty::get($this->db(), $player->partyId);
        if ($player->id != $playerParty->uid) {
            $this->flash->error("你不是队长，操作失败");
            $this->doRawCmd($this->lastAction());
        }
        $this->db()->delete('player_party', ['id' => $partyId]);
        $members = $this->db()->select('player_party_member', ['id', 'uid'], [
            'party_id' => $partyId,
            'status[>]' => 0
        ]);
        $ids = [];
        foreach ($members as $v) {
            $ids[] = $v['uid'];
        }
        if (!empty($ids)) {
            $this->db()->update('game1', ['party_id' => 0], ['id' => $ids]);
        }
        $this->db()->delete('player_party_member', ['party_id' => $partyId]);
        $this->flash->success("操作成功，队伍已解散");
        $this->doRawCmd($this->lastAction());
    }
}