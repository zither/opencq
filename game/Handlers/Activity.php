<?php
namespace Xian\Handlers;

use Xian\AbstractHandler;
use Xian\Object\Location;
use Xian\Player\PrivateItem;
use function player\addPlayerEquip;
use function player\addPlayerStackableItem;
use function player\getPlayerById;

class Activity extends AbstractHandler
{
    public function showList()
    {
        $this->display('activity/list');
    }

    public function showQiandao()
    {
        $privateItem = new PrivateItem(
            $this->db(),
            $this->uid(),
            PrivateItem::TYPE_OPERATION_IDENTITY
        );
        $item = $privateItem->getByKey('qiandao');
        $isToday = false;
        if (!empty($item)) {
            $today = strtotime(date('Y-m-d 00:00:00'));
            $lastTimestamp = strtotime($item['updated_at']);
            $isToday = $lastTimestamp > $today;
            $isYesterday = !$isToday && $lastTimestamp > strtotime('-1 day', $today);
        }

        if (!empty($item) && !$isToday && !$isYesterday) {
            $item['v'] = 0;
        }

        $sequence = empty($item) ? 1 : ($item['v'] > 6 ? 7 : $item['v'] + 1);
        // 今日未签到
        if (!$isToday) {
            $activityKey = "qiandao_$sequence";
            $activity = $this->db()->get('activity', '*', ['name' => $activityKey]);
            $gifts = [];
            if (!empty($activity['gold'])) {
                $gifts[] = [
                    'id' => 0,
                    'name' => '金币',
                    'amount' => $activity['gold'],
                ];
            }
            if (!empty($activity['items'])) {
                $tmp = explode(',', $activity['items']);
                $ids = [];
                $itemsMap = [];
                foreach ($tmp as $v) {
                    $arr = explode('|', $v);
                    if (count($arr) !== 2) {
                        continue;
                    }
                    $ids[] = $arr[0];
                    $itemsMap[$arr[0]] = $arr[1];
                }

                if (!empty($ids)) {
                    $items = $this->db()->select('item', '*', ['id' => $ids]);
                    foreach ($items as $v) {
                        $gifts[] = [
                            'id' => $v['id'],
                            'name' => $v['ui_name'] ?? $v['name'],
                            'amount' => $itemsMap[$v['id']],
                        ];
                    }
                }

            }
        }

        $data = [
            'item' => $item,
            'gifts' => $gifts ?? [],
            'is_today' => $isToday ?? false,
            'is_yesterday' => $isYesterday ?? false,
        ];
        $this->display('activity/qiandao', $data);
    }

    public function doQiandao()
    {
        $privateItem = new PrivateItem(
            $this->db(),
            $this->uid(),
            PrivateItem::TYPE_OPERATION_IDENTITY
        );
        $item = $privateItem->getByKey('qiandao');
        $isToday = false;
        if (!empty($item)) {
            $today = strtotime(date('Y-m-d 00:00:00'));
            $lastTimestamp = strtotime($item['updated_at']);
            $isToday = $lastTimestamp > $today;
            $isYesterday = !$isToday && $lastTimestamp > strtotime('-1 day', $today);
        }

        if (!empty($item) && !$isToday && !$isYesterday) {
            $item['v'] = 0;
        }

        if ($isToday) {
            $this->flash->error('今日已签到');
            $this->doRawCmd($this->lastAction());
        }
        $sequence = empty($item) ? 1 : ($item['v'] > 6 ? 7 : $item['v'] + 1);
        $activityKey = "qiandao_$sequence";
        $activity = $this->db()->get('activity', '*', ['name' => $activityKey]);
        if (empty($activity)) {
            $this->flash->error('奖励未就绪，签到失败');
            $this->doRawCmd($this->lastAction());
        }

        // 活得金币
        $this->db()->update('game1', ['uyxb[+]' => $activity['gold']], ['id' => $this->uid()]);
        // 增加签到记录
        if (empty($item)) {
            $privateItem->add('qiandao', 1, PrivateItem::TYPE_OPERATION_IDENTITY);
        } else {
            $privateItem->updateByKey('qiandao', $item['v'] + 1);
        }

        if (!empty($activity['items'])) {
            $tmp = explode(',', $activity['items']);
            $ids = [];
            $itemsMap = [];
            foreach ($tmp as $v) {
                $arr = explode('|', $v);
                if (count($arr) !== 2) {
                    continue;
                }
                $ids[] = $arr[0];
                $itemsMap[$arr[0]] = $arr[1];
            }

            if (!empty($ids)) {
                $player = getPlayerById($this->db(), $this->uid(), true);
                $items = $this->db()->select('item', '*', ['id' => $ids]);
                foreach ($items as $v) {
                    $amount = $itemsMap[$v['id']];
                    if ($v['type'] == 1 || $v['type'] == 3) {
                        addPlayerStackableItem($this->db(), $this->uid(), $v, $itemsMap[$v['id']]);
                    } else if ($v['type'] == 2) {
                        $loc = Location::get($this->db(), $player->nowmid);
                        $source = [
                            'location' => $loc->name,
                            'monster' => '每日签到',
                            'player' => $player->name,
                        ];
                        while ($amount > 0) {
                            addPlayerEquip($this->db(), $this->uid(), $v, $source, true);
                            $amount--;
                        }
                    }
                }
            }
        }

        $this->flash->success('签到成功');
        $this->doRawCmd($this->lastAction());
    }

    public function showRedeemCode()
    {
        $label = $this->params['label'] ?? '';
        $message = $this->params['message'] ?? '';
        $code = $this->params['code'] ?? '';

        if ($code) {
            $activity = $this->db()->get('activity', '*', ['name' => $code]);
            $items = [];
            if (!empty($activity)) {
                $items = $this->getActivityItems($activity);
            }
        }

        $data = [
            'label' => $label,
            'message' => $message,
            'code' => $code,
            'items' => $items
        ];
        $this->display('activity/redeem_code', $data);
    }

    public function doRedeemCode()
    {
        $prefix = 'RC01_';
        $code = $this->postParam('code');

        $codeCmd = 'cmd=activity-redeem-code';
        $activityListCmd = 'cmd=activity-list';
        if (strpos($code, $prefix) !== 0) {
            $this->flash->error('非法兑换码');
            $this->doRawCmd($codeCmd);
        }


        $activity = $this->db()->get('activity', '*', ['name' => $code]);
        if (empty($activity)) {
            $this->flash->error('无效兑换码');
            $this->doRawCmd($codeCmd);
        }

        $identity = $this->getIdentity($code, PrivateItem::TYPE_OPERATION_IDENTITY);
        if (!empty($identity)) {
            $this->flash->error('兑换码已领取，请勿重复操作');
            $this->doRawCmd($activityListCmd);
        }

        // 获得道具
        $this->gainActivityItems($activity);
        // 增加
        $this->addIdentity($code, 1,PrivateItem::TYPE_OPERATION_IDENTITY);

        $this->flash->success('兑换成功，请查看背包');
        $this->doRawCmd($activityListCmd);
    }

    /**
     * 获取活动奖励
     *
     * @param array $activity
     */
    protected function gainActivityItems(array $activity, string $source = '活动道具')
    {
        if (empty($activity)) {
            return;
        }

        // 活得金币
        if ($activity['gold']) {
            $this->db()->update('game1', ['uyxb[+]' => $activity['gold']], ['id' => $this->uid()]);
        }

        if (!empty($activity['items'])) {
            $tmp = explode(',', $activity['items']);
            $ids = [];
            $itemsMap = [];
            foreach ($tmp as $v) {
                $arr = explode('|', $v);
                if (count($arr) !== 2) {
                    continue;
                }
                $ids[] = $arr[0];
                $itemsMap[$arr[0]] = $arr[1];
            }

            if (!empty($ids)) {
                $player = getPlayerById($this->db(), $this->uid(), true);
                $items = $this->db()->select('item', '*', ['id' => $ids]);
                foreach ($items as $v) {
                    $amount = $itemsMap[$v['id']];
                    if ($v['type'] == 1 || $v['type'] == 3) {
                        addPlayerStackableItem($this->db(), $this->uid(), $v, $itemsMap[$v['id']]);
                    } else if ($v['type'] == 2) {
                        $loc = Location::get($this->db(), $player->nowmid);
                        $source = [
                            'location' => $loc->name,
                            'monster' => $source,
                            'player' => $player->name,
                        ];
                        while ($amount > 0) {
                            addPlayerEquip($this->db(), $this->uid(), $v, $source, true);
                            $amount--;
                        }
                    }
                }
            }
        }
    }

    protected function getActivityItems(array $activity): array
    {
        $gifts = [];
        if (!empty($activity['gold'])) {
            $gifts[] = [
                'id' => 0,
                'name' => '金币',
                'amount' => $activity['gold'],
            ];
        }
        if (!empty($activity['items'])) {
            $tmp = explode(',', $activity['items']);
            $ids = [];
            $itemsMap = [];
            foreach ($tmp as $v) {
                $arr = explode('|', $v);
                if (count($arr) !== 2) {
                    continue;
                }
                $ids[] = $arr[0];
                $itemsMap[$arr[0]] = $arr[1];
            }

            if (!empty($ids)) {
                $items = $this->db()->select('item', '*', ['id' => $ids]);
                foreach ($items as $v) {
                    $gifts[] = [
                        'id' => $v['id'],
                        'name' => $v['ui_name'] ?? $v['name'],
                        'amount' => $itemsMap[$v['id']],
                    ];
                }
            }

        }
        return $gifts;
    }

    /**
     * 添加标识
     *
     * @param string $key
     * @param mixed $value
     * @param int $type
     * @return bool
     */
    protected function addIdentity(string $key, $value, int $type): bool
    {
        $privateItem = new PrivateItem($this->db(), $this->uid(), $type);
        return $privateItem->add($key, $value, $type);
    }

    /**
     * 获取标识
     *
     * @param string $key
     * @param int $type
     * @return array
     */
    protected function getIdentity(string $key, int $type): array
    {
        $privateItem = new PrivateItem($this->db(), $this->uid(), $type);
        return $privateItem->getByKey($key);
    }
}