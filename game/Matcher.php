<?php
namespace Xian;

use Lua;
use Medoo\Medoo;
use player\Player;
use Xian\Player\PrivateItem;

class Matcher
{
    /**
     * @var Medoo
     */
    protected $db;

    /**
     * @var Lua
     */
    protected $lua;

    /**
     * @var int
     */
    protected $uid;

    /**
     * @var Player
     */
    protected $player;

    /**
     * Condition constructor.
     * @param Medoo $db
     * @param Lua $lua
     * @param int $uid
     */
    public function __construct(Medoo $db, Lua $lua, int $uid = 0)
    {
        $this->db  = $db;
        $this->lua = $lua;
        $this->registerCallbacks();
        if ($uid) {
            $this->withUid($uid);
        }
    }

    /**
     * @param int $uid
     */
    public function withUid(int $uid)
    {
        $this->uid = $uid;
        $this->player = null;
    }

    public function validate(array $condition)
    {
        // 设置用户编号
        if (empty($condition['matchers'])) {
            return [true, ''];
        }
        $result = $this->execute($condition['matchers']);
        if ($result) {
            $info =  $condition['success_info'] ?? '';
        } else {
            $info =  $condition['failure_info'] ?? '';
        }
        return [$result, $info];
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function execute(string $code)
    {
        return $this->lua->eval($code);
    }

    protected function checkMatcher(string $matcher): bool
    {
        if (strpos($matcher, ':') === false) {
            return false;
        }
        $player = $this->getPlayer();
        $arr = explode(':', $matcher);
        switch ($arr[0]) {
            case 'task':
                if (!isset($arr[1]) || !isset($arr[2])) {
                    return false;
                }
                $taskCount = $this->db->count('player_task', [
                    'task_id' => (int)$arr[1],
                    'uid' => $player->id,
                    'status' => (int)$arr[2],
                ]);
                return $taskCount > 0;
            default:
                return true;
        }
    }

    protected function getPlayer()
    {
        if (!empty($this->player)) {
            return $this->player;
        }
        $this->player = \player\getPlayerById($this->db, $this->uid, true);
        return $this->player;
    }

    protected function registerCallbacks()
    {
        $this->lua->registerCallback('has_completed_task', [$this, 'hasCompletedTask']);
        $this->lua->registerCallback('player_attr_num', [$this, 'playerAttributeNumber']);
        $this->lua->registerCallback('count_killed_private_monster', [$this, 'countKilledPrivateMonster']);
        $this->lua->registerCallback('count_operation', [$this, 'countOperation']);
        $this->lua->registerCallback('has_item', [$this, 'HasItem']);
        return true;
    }

    public function hasCompletedTask(int $taskId)
    {
        $player = $this->getPlayer();
        $taskCount = $this->db->count('player_task', [
            'task_id' => $taskId,
            'uid' => $player->id,
            'status' => 3,
        ]);
        return $taskCount > 0;
    }

    public function playerAttributeNumber(string $attr)
    {
        $player = $this->getPlayer();
        if (isset($player->$attr)) {
            return (int)$player->$attr;
        }
        return 0;
    }

    public function countKilledPrivateMonster(int $id)
    {
        $player = $this->getPlayer();
        $count = $this->db->get('player_private_items', ['v'], [
            'uid' => $player->id,
            'type' => PrivateItem::TYPE_MONSTER_KILLED,
            'k' => $id
        ]);
        if (empty($count)) {
            return 0;
        }
        return (int)$count['v'];
    }

    public function countOperation(string $key, int $areaId  = 0)
    {
        $player = $this->getPlayer();
        $count = $this->db->get('player_private_items', '*', [
            'uid' => $player->id,
            'type' => PrivateItem::TYPE_OPERATION_IDENTITY,
            'k' => $key,
            'area_id' => $areaId,
        ]);
        if (empty($count)) {
            return 0;
        }
        return (int)$count['v'];
    }

    public function hasItem(int $id,  int $amount = 1)
    {
        $player = $this->getPlayer();
        $item = $this->db->get('player_item', ['amount'], [
            'uid' => $player->id,
            'item_id' => $id,
        ]);
        if (empty($item) || $item['amount'] < $amount) {
            return false;
        }
        return true;
    }

    /**
     * @FIXME 由于 lua 对象中引用了 $this，对象不会自动调用析构函数，必须手动调用，目前在 Condition 类析构函数中调用
     */
    public function __destruct()
    {
        unset($this->db, $this->lua, $this->player);
    }
}