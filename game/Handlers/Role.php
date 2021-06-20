<?php

namespace Xian\Handlers;

use Medoo\Medoo;
use player\Player;
use Xian\AbstractHandler;
use Xian\Event;
use Xian\Helper;
use Xian\Object\Pet;
use Xian\Object\PlayerParty;
use function player\changeAllPlayerTaskConditionsByItemId;
use function player\getPlayerEquipsByIds;
use function player\getPlayerItem;
use function player\getPlayerItemById;
use function player\updateTaskStatusWhenFinished;

class Role extends AbstractHandler
{
    use CommonTrait;

    public function showStatus()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());

        $data['player'] = $player;
        $this->display('zhuangtai', $data);
    }

    public function showSkills()
    {
        $type = $this->params['type'] ?? null;
        $condition = ['uid' => $this->uid()];
        if ($type) {
            $condition['type'] = $type;
        }
        $skills = $this->db()->select('player_skill', ['[>]skills' => ['skill_id' => 'id']], [
            'player_skill.id',
            'player_skill.skill_id',
            'player_skill.level',
            'skills.name',
            'skills.info',
            'skills.level(require_level)',
            'skills.type',
        ], $condition);
        $levelMap = [
            '1' => '初级',
            '2' => '中级',
            '3' => '高级',
            '4' => '专家',
        ];
        foreach ($skills as &$skill) {
            if (isset($levelMap[$skill['level']])) {
                $skill['ui_level'] = $levelMap[$skill['level']];
            } else {
                $skill['ui_level'] = $levelMap[1];
            }
        }
        $data = [
            'skills' => $skills,
        ];
        if ($type) {
            $data['type'] = $type;
        }
        $this->display('player_skills', $data);
    }

    public function showSKillInfo()
    {
        $id = $this->params['id'] ?? 0;
        if (!$id) {
            $this->flash->error('无效技能！');
            $this->doRawCmd($this->lastAction());
        }
        $skill = $this->db()->get('player_skill', ['[>]skills' => ['skill_id' => 'id']], [
            'player_skill.id',
            'player_skill.skill_id',
            'player_skill.level',
            'player_skill.score',
            'player_skill.max_score',
            'skills.name',
            'skills.info',
            'skills.level(require_level)',
            'skills.type',
            'skills.in_combat',
            'skills.outside_combat',
        ], [
            'player_skill.id' => $id,
            'uid' => $this->uid()
        ]);
        $levelMap = [
            '1' => '初级',
            '2' => '中级',
            '3' => '高级',
            '4' => '专家',
        ];
        $skill['ui_level'] = $levelMap[$skill['level']] ?? $levelMap[1];
        $skillEffects = $this->db()->select('skill_effects', '*', ['skill_id' => $skill['skill_id']]);
        $effectDescArr = [];
        foreach ($skillEffects as $effect) {
            $effect['amount'] = ceil($effect['amount'] * (1 + Helper::SKILL_LEVEL_RATE * ($skill['level'] - 1)));
            $effectDescArr[] = $this->effectDescription($effect);
        }
        $data = [
            'skill' => $skill,
            'effects' => $effectDescArr
        ];
        $this->display('player_skill_info', $data);
    }

    protected function effectDescription($effect)
    {
        if ($effect['attack_type'] == 0) {
            $name = $effect['ui_info'];
            if ($effect['is_column']) {
                switch ($effect['column']) {
                    case 'wugong':
                        $column = '物攻';
                        break;
                    case 'fagong':
                        $column = '法攻';
                        break;
                    case 'wufang':
                        $column = '物防';
                        break;
                    case 'fafang':
                        $column = '法防';
                        break;
                    case 'mingzhong':
                        $column = '命中';
                        break;
                    case 'shanbi':
                        $column = '闪避';
                        break;
                    case 'baoji':
                        $column = '暴击';
                        break;
                    case 'shenming':
                        $column = '神明';
                        break;
                    case 'hp':
                        $column = '气血';
                        break;
                    case 'max_hp':
                        $column = '气血上限';
                        break;
                }
            } else {
                if ($effect['is_wushang']) {
                    $column = '物伤';
                } else if ($effect['is_fashang']) {
                    $column = '法伤';
                } else if ($effect['is_wumian']) {
                    $column = '物免';
                } else if ($effect['is_famian']) {
                    $column = '法免';
                } else if ($effect['is_mingzhong']) {
                    $column = '命中率';
                } else if ($effect['is_shanbi']) {
                    $column = '闪避率';
                } else if ($effect['is_baoji']) {
                    $column = '暴击率';
                } else if ($effect['is_shenming']) {
                    $column = '抗暴率';
                }
            }
            if ($effect['effect_type'] == 1) {
                $type = $effect['amount'] > 0 ? '提升' : '降低';
            } else {
                $type = $effect['amount'] > 0 ? '增加' : '减少';
            }

            $amount = abs($effect['amount']);
            $turns = $effect['turns'];
            $percent = $effect['effect_type'] == 1 ? '%%' : '点';
            $desc = "<span class=''>{$name}</span>: %s的{$column}{$type}{$amount}{$percent}，持续{$turns}回合";
            if ($effect['target_mode'] == 1) {
                $target = '自身';
            } else {
                $target = $effect['target'] == 1 ? '敌方' : '己方';
                switch ($effect['target_mode']) {
                    case 2:
                        $target .= "随机{$effect['target_num']}个单位";
                        break;
                    case 3:
                        $target .= '全体目标';
                        break;
                    case 4:
                        $target .= "随机直线上的全部单位";
                        break;
                }
            }

            return sprintf($desc . "。", $target);
        }

        $type = $effect['attack_type'] == 1 ? '物理' : '法术';
        $amount = abs($effect['amount']);
        $turns = $effect['turns'];
        $percent = $effect['effect_type'] == 1 ? '%%' : '点';
        if ($effect['effect_type'] == 1) {
            $desc = "对%s造成{$type}攻击力{$amount}{$percent}威力的{$type}伤害";
        } else {
            $desc = "对%s造成{$type}攻击力附加{$amount}{$percent}的{$type}伤害";
        }
        if ($turns > 1) {
            $desc .= "，持续{$turns}回合";
        }
        if ($effect['target_mode'] == 1) {
            $target = '自身';
        } else {
            $target = $effect['target'] == 1 ? '敌方' : '己方';
            switch ($effect['target_mode']) {
                case 2:
                    $target .= '随机';
                    break;
                case 3:
                    $target .= '血量最少的';
                    break;
            }
            $target .= $effect['target_num'] . "个单位";
        }

        return sprintf($desc . "。", $target);
    }

    public function showEquips()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid(), true);

        $equipIds = [
            $player->tool1,
            $player->tool2,
            $player->tool3,
            $player->tool4,
            $player->tool5,
            $player->tool6,
            $player->tool7,
            $player->tool8,
            $player->tool9,
            $player->tool10,
            $player->tool11,
            $player->tool12,
        ];
        $validEquipIds = array_filter($equipIds, function ($v) {
            return $v > 0;
        });
        $equips = getPlayerEquipsByIds($this->db(), $validEquipIds, false);
        $equipMaps = [];
        foreach ($equips as $v) {
            $v->qualityColor = $this->getQualityColor($v->quality);
            $equipMaps[$v->id]  = $v;
        }

        $tools = [
            '武器' => $equipMaps[$player->tool1] ?? null,
            '衣服' => $equipMaps[$player->tool2] ?? null,
            '头盔' => $equipMaps[$player->tool3] ?? null,
            '项链' => $equipMaps[$player->tool4] ?? null,
            '手镯(左)' => $equipMaps[$player->tool5] ?? null,
            '手镯(右)' => $equipMaps[$player->tool6] ?? null,
            '戒指(左)' => $equipMaps[$player->tool7] ?? null,
            '戒指(右)' => $equipMaps[$player->tool8] ?? null,
            '腰带' => $equipMaps[$player->tool9] ?? null,
            '鞋子' => $equipMaps[$player->tool10] ?? null,
            '宝石' => $equipMaps[$player->tool11] ?? null,
            '勋章' => $equipMaps[$player->tool12] ?? null,
        ];

        $data['player'] = $player;
        $data['tools'] = $tools;

        $this->display('show_equips', $data);
    }


    public function showOtherStatus()
    {
        $db = $this->game->db;
        $uid = $this->params['uid'] ?? null;
        $otherPlayer = \player\getplayer1($uid, $db);
        $other = \player\getPlayerById($db, $otherPlayer->id);
        if (!$other->id) {
            $this->doRawCmd($this->lastAction());
        }

        $pkcmd = $this->encode("cmd=do-pvp&uid=$uid&begin=1");
        $clubplayer = \player\getclubplayer_once($this->db(), $other->id);

        // 装备列表
        $equipMaps = [];
        $equipIds = [
            $other->tool1,
            $other->tool2,
            $other->tool3,
            $other->tool4,
            $other->tool5,
            $other->tool6,
            $other->tool7,
            $other->tool8,
            $other->tool9,
            $other->tool10,
            $other->tool11,
            $other->tool12,
        ];
        $validEquipIds = array_filter($equipIds, function ($v) {
            return $v > 0;
        });
        $equips = getPlayerEquipsByIds($this->db(), $validEquipIds, false);
        foreach ($equips as $v) {
            $v->qualityColor = $this->getQualityColor($v->quality);
            $equipMaps[$v->id]  = $v;
        }
        $tools = [
            '武器' => $other->tool1,
            '衣服' => $other->tool2,
            '头盔' => $other->tool3,
            '项链' => $other->tool4,
            '手镯(左)' => $other->tool5,
            '手镯(右)' => $other->tool6,
            '戒指(左)' => $other->tool7,
            '戒指(右)' => $other->tool8,
            '腰带' => $other->tool9,
            '鞋子' => $other->tool10,
            '宝石' => $other->tool11,
            '勋章' => $other->tool12,
        ];
        foreach ($tools as $k => $v) {
            if ($v <= 0 || !isset($equipMaps[$v])) {
                unset($tools[$k]);
                continue;
            }
            $tools[$k] = $equipMaps[$v];
        }

        $im = $this->db()->count('player_relationship', [
            'uid' => $this->uid(),
            'tid' => $other->id,
            'type' => 1, // 好友
        ]);

        $skills = $this->db()->select('player_skill', ['[>]skills' => ['skill_id' => 'id']], [
            'player_skill.id',
            'player_skill.level',
            'skills.name',
        ], [
            'player_skill.uid' =>  $uid,
            'ORDER' => ['skills.level' => 'DESC']
        ]);
        foreach ($skills as &$v) {
            $v['skill_level'] = $this->skillLevelMap[$v['level']];
            unset($v);
        }

        if ($other->partyId) {
            $playerParty = PlayerParty::get($this->db(), $other->partyId);
        }

        // 获取玩家宠物相关信息
        if ($other->cw) {
            $pet = Pet::get($this->db(), $other->cw);
            if ($pet->id) {
                $data['pet'] = $pet;
                $data['pet_color'] = 'color-blue';
                $data['pet_level_text'] = '初级';
                foreach ($skills as $v) {
                    if ($v['id'] == $pet->playerSkillId) {
                        $data['pet_color'] = Helper::getQualityColor($v['level']);
                        $data['pet_level_text'] = $v['skill_level'];
                        break;
                    }
                }
            }
        }

        $data['is_self'] = $this->uid() == $other->id;
        $data['is_im'] = $im > 0;
        $data['pk_link'] = $pkcmd;
        $data['add_im_link'] = $this->encode("cmd=add-im&uid=$uid");
        $data['delete_im_link'] = $this->encode("cmd=delete-im&uid=$uid");
        $data['chat_link'] = $this->encode("cmd=sendliaotian&imuid=$uid&ltlx=im");
        $data['player'] = $other;
        $data['tools'] = $tools;
        $data['toolsMap'] = $equipMaps;
        $data['skills'] = $skills;
        $data['is_leader'] = isset($playerParty) && $playerParty->uid == $other->id;
        $data['party'] = $playerParty ?? null;

        $this->display('other_zhuangtai', $data);
    }

    /**
     * 脱下装备
     */
    public function takeOff()
    {
        $equipId = $this->params['equip_id'] ?? 0;
        if (empty($equipId)) {
            $this->doRawCmd($this->lastAction());
        }
        $uid = $this->uid();
        $equips = $this->db()->get('game1', [
            'tool1',
            'tool2',
            'tool3',
            'tool4',
            'tool5',
            'tool6',
            'tool7',
            'tool8',
            'tool9',
            'tool10',
            'tool11',
            'tool12',
        ], ['id' => $uid]);
        foreach ($equips as $k => $v) {
            if ($v == $equipId) {
                $playerItem = getPlayerItemById($this->db(), $equipId, $uid);
                $this->db()->update('game1', [$k => 0], ['id' => $uid]);
                $this->db()->update('player_item', ['storage' => 1], ['id' => $equipId]);
                // 更新道具对应的未完成任务条件
                changeAllPlayerTaskConditionsByItemId($this->db(), $this->uid(), $playerItem->itemId, 1);
                // 更新任务状态
                updateTaskStatusWhenFinished($this->db(), $this->uid());
               break;
            }
        }
        $this->doRawCmd('cmd=show-equips');
    }
    
    public function selectEquip()
    {
        $uid = $this->uid();
        $tool = $this->params['tool'] ?? '';
        if ($uid < 0 || empty($tool) || !isset($this->toolNames[$tool])) {
            $this->flash->error('请选择装备部位');
            $this->doRawCmd($this->lastAction());
        }
        // 获取已装备的装备编号
        $equips = $this->db()->get('game1', [
            'tool1',
            'tool2',
            'tool3',
            'tool4',
            'tool5',
            'tool6',
            'tool7',
            'tool8',
            'tool9',
            'tool10',
            'tool11',
            'tool12',
        ], ['id' => $uid]);
        $ids = [];
        foreach ($equips as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $ids[] = $v;
        }
        $whereCondition = [
            'player_item.uid' => $uid,
            'player_item.storage' => 1,
            'player_equip_info.equip_type' => $this->toolNames[$tool]
        ];
        if (!empty($ids)) {
            $whereCondition['player_item.id[!]'] = $ids;
        }
        $equips = $this->db()->select('player_item', [
            '[>]item' => ['item_id' => 'id'],
            '[>]player_equip_info' => ['sub_item_id' => 'id'],
        ], [
            'player_item.id',
            'player_item.uid',
            'player_item.item_id',
            'player_item.sub_item_id',
            'player_equip_info.name',
            'player_equip_info.ui_name',
            'player_equip_info.level',
            'player_equip_info.equip_type',
            'player_equip_info.shengxing',
            'player_equip_info.qianghua',
            'player_equip_info.quality',
        ], $whereCondition);
        foreach ($equips as &$v) {
            $v['quality_color'] = $this->getQualityColor($v['quality']);
            unset($v);
        }
        $data = [
            'uid' => $uid,
            'equips' => $equips,
            'tool' => $tool,
        ];
        $this->display('select_equip', $data);
    }

    /**
     * 穿戴装备
     */
    public function wear()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());
        $back = $this->encode('cmd=show-equips');
        $zbnowid = $this->params['zbnowid'] ?? null;
        $tool = $this->params['tool'] ?? null;
        $arr = [
            $player->tool1,
            $player->tool2,
            $player->tool3,
            $player->tool4,
            $player->tool5,
            $player->tool6,
            $player->tool7,
            $player->tool8,
            $player->tool9,
            $player->tool10,
            $player->tool11,
            $player->tool12,
        ];
        if ($zbnowid && $tool) {
            if (!in_array($zbnowid, $arr)) {
                $nowzb = \player\getPlayerEquip($this->db(), $zbnowid);
                if ($nowzb->storage != 1) {
                    $this->flash->error('背包内没有该装备');
                    $this->doCmd($back);
                }
                if ($nowzb->sex && $nowzb->sex != $player->sex) {
                    $this->flash->set('error', '不满足装备性别要求');
                    $this->doCmd($back);
                }
                if ($nowzb->manualId && $nowzb->manualId != $player->manualId) {
                    $this->flash->set('error', '无法穿戴其他职业装备');
                    $this->doCmd($back);
                }
                if ($nowzb->uid != $player->id) {
                    $this->flash->set('error', '你没有该装备，无法装备');
                    $this->doCmd($back);
                } elseif ($nowzb->level > $player->level) {
                    $this->flash->set('error', '玩家等级不足，无法装备');
                    $this->doCmd($back);
                }elseif($nowzb->equipType && $nowzb->equipType != $this->toolNames[$tool]){
                    $this->flash->set('error', '装备种类不符合,无法装备');
                    $this->doCmd($back);
                }else{
                    $toolIndex = array_search($tool, array_keys($this->toolNames));
                    $column = sprintf('tool%d', $toolIndex);
                    // 如果之前穿戴了其他装备，需要将其移动到背包
                    if (!empty($player->$column)) {
                        $db->update('player_item', ['storage' => 1], ['id' => $player->$column]);
                    }
                    $db->update('game1', [$column => $zbnowid], ['id' => $this->uid()]);
                    $db->update('player_item', ['storage' => 2], ['id' => $zbnowid]);

                    // 更新道具对应的未完成任务条件
                    changeAllPlayerTaskConditionsByItemId($db, $this->uid(), $nowzb->itemId, -1);
                    // 更新任务状态
                    updateTaskStatusWhenFinished($db, $this->uid());
                }
            }
        }
        $this->doCmd($back);
    }

    public function showTupo()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());
        $tupocmd = $this->encode("cmd=do-tupo");
        $gonowmid = $this->encode("cmd=gomid&newmid=$player->nowmid");
        $tupo = \player\istupo($this->uid(), $db);
        $pingjing = [];
        $data = [];
        $data['mid'] = $this->db()->get('mid', ['mname(name)', 'lingqi'], ['mid' => $player->nowmid]);
        $data['gonowmid'] = $gonowmid;
        $data['can_tupo'] = $tupo > 0;
        $data['pingjing'] = $pingjing;
        $data['tupocmd'] = $tupocmd;
        $data['player'] = $player;
        $data['rates'] = $this->getTupoRates($player, $tupo);
        unset($this->data['tupo']);

        $this->display('tupo', $data);
    }

    public function doTupo()
    {
        $db = $this->game->db;
        $player = \player\getPlayerById($db, $this->uid());
        $tupo = \player\istupo($this->uid(), $db);
        $back = $this->encode('cmd=tupo');

        $rates = $this->getTupoRates($player, $tupo);
        $successRate = $rates['success'];
        $random = mt_rand(0,100);
        // 突破失败
        if ($random > $successRate) {
            // 减少角色血量
            \player\changeplayersx('hp', floor($player->hp * 0.2), $this->uid(), $db);
            $duration = 12 * 60 * 60;
            if (!isset($rates['effects'])) {
                // 增加心境大跌 debuff
                $db->insert('player_effects', [
                    'uid' => $player->id,
                    'column' => 'tupo_rate_reduce',
                    'amount' => -50,
                    'is_column' => 0,
                    'is_temporary' => 1,
                    'is_custom' => 1,
                    'effect_type' => 2,
                    'duration' => $duration,
                    'end_at' => date('Y-m-d H:i:s', time() + $duration),
                    'desc' => '突破机率副作用，防止连续突破'
                ]);
            } else {
                // 延迟神魂不稳的时间12小时
                $endAt = Medoo::raw(sprintf('TIMESTAMPADD(SECOND, %d, end_at)', $duration));
                $db->update('player_effects', ['end_at' => $endAt], ['uid' => $player->id, 'k' => 'tupo_rate_reduce']);
            }
            $this->flash->set('error', '你突破失败了，心境大跌，无法继续下去');
            $this->doCmd($back);
        }
        // 突破成功提高角色等级
        \player\upplayerlv($this->db(), $player->id);
        // 修改人物等级
        $player->level += 1;

        // 查找角色下一个瓶颈
        $pingjing = $db->get('manual_level', '*', [
            'manual_id' => $player->manualId,
            'sequence' => $player->manualSequence,
            'is_max_exp' => 1,
            'level[>=]' => $player->level,
            'ORDER' => ['level' => 'ASC']
        ]);
        $message = "成功突破当前功法瓶颈";
        // 如果该阶段没有后续瓶颈，则需要提高角色功法境界
        if (empty($pingjing)) {
            $manualLevel = $db->get('manual_level', ['id', 'name', 'level', 'layer'], [
                'manual_id' => $player->manualId,
                'is_min_ex' => 1,
                'sequence' => $player->manualSequence + 1,
                'level[>=]' => $player->level,
                'ORDER' => ['level' => 'ASC']
            ]);

            // 修改人物功法等级
            $db->update('player_manual', ['manual_level_id' => $manualLevel['id']], [
                'uid' => $player->id,
                'manual_id' => $player->manualId
            ]);
            $message = "成功突破功法瓶颈，进入到下{$manualLevel['name']}境界";
            // 获取新境界的奖励
            Helper::getManualLevelBonuses($this->db(), $player->id, $manualLevel['id']);
        }
        $this->flash->set('success', $message);
        $this->doCmd($back);
    }

    /**
     * 获取突破成功率
     *
     * @param Player $player
     * @param int $tupo
     * @return array
     */
    protected function getTupoRates(Player $player, int $tupo)
    {
        // 影响突破的因素
        // 1.环境，需要给地图添加相关参数，比如灵气值，20%
        // 2.人物悟性 20%
        // 3.人物心境 10%
        // 4.突破本身的成功率，大阶和小阶难度不同，50%
        $clmid = \player\getmid($player->nowmid, $this->game->db);
        $envRate = ($clmid->lingqi / 100) * 20;
        $wuxingRate = ($player->wuxing / 100) * 20;

        // 受伤扣除50心境
        $hurt = ($player->hp / $player->maxhp) * 100 < 80 ? 50 : 0;
        // 装备不齐也扣心境
        $emptySlots = 0;
        for ($i = 1; $i <= 6; $i++) {
            $k = sprintf('tool%d', $i);
            if (!$player->$k) {
                $emptySlots++;
            }
        }
        $empty = ($emptySlots / 6)  * 50;
        $xinjingRate = ((100 - $hurt - $empty) / 100) * 10;

        // 突破阶段本身的难度
        $pingjing = $this->game->db->get('manual_level', '*', [
            'manual_id' => $player->manualId,
            'sequence' => $player->manualSequence,
            'is_max_exp' => 1,
            'level[>=]' => $player->level,
            'ORDER' => ['level' => 'ASC']
        ]);
        $jieduanRate = ($pingjing['rate'] / 100) * 50;

        $yaopinEffect = $this->game->db->get('player_effects', '*', [
            'uid' => $player->id,
            'is_custom' => 1,
            'column' => 'tupo_rate_inc'
        ]);
        $yaopinRate = $yaopinEffect ? $yaopinEffect['amount'] : 0;

        // 心境大跌
        $buff = 0;
        $tupoReduce = $this->game->db->get('player_effects', '*', [
            'uid' => $player->id,
            'is_custom' => 1,
            'column' => 'tupo_rate_reduce'
        ]);
        if (!empty($tupoReduce) && strtotime($tupoReduce['end_at']) > time()) {
            $buff += $tupoReduce['amount'];
        }
        $successRate =  $envRate + $wuxingRate + $xinjingRate + $jieduanRate + $buff + $yaopinRate;
        $successRate = $successRate < 0 ? 0 : $successRate;
        $result = [
            'env' => $envRate,
            'wuxing' => round($wuxingRate, 2),
            'xinjing' => round($xinjingRate, 2),
            'jieduan' => round($jieduanRate, 2),
            'success' => round($successRate, 2),
            'pingjing' => $pingjing,
        ];
        if ($buff != 0) {
            $result['effects'] = round($buff, 2);
        }
        if ($yaopinEffect) {
            $result['yaopin'] = $yaopinEffect;
        }

        return $result;
    }
}