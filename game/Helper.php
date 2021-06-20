<?php
namespace Xian;

use Medoo\Medoo;
use player\Player;
use Xian\Object\Location;
use Xian\Player\PrivateItem;
use function player\addPlayerEquip;

class Helper
{
    const SKILL_LEVEL_RATE = 0.1;
    const SKILL_INIT_SCORE = 600;

    public static $pool;

    /**
     * @var array
     */
    public static $configs;

    /**
     * @var Medoo
     */
    public static $db;

    /**
     * @return array
     */
    public static function loadConfigs(string $file)
    {
        static::$configs = json_decode(file_get_contents($file), true);
        return static::$configs;
    }

    public static function dump($data)
    {
        $server = self::$configs['app_server'] ?? '';
        switch ($server) {
            case 'roadrunner':
            case 'workerman':
            case 'swow':
                error_log(json_encode($data));
                break;
            default:
                echo "<pre>";
                print_r($data);
                echo "</pre>";
        }
    }

    public static function createDB(string $host, string $databaseName, string $user, string $password)
    {
        $db = new Medoo([
            'database_type' => 'mysql',
            'database_name' => $databaseName,
            'server' => $host,
            'username' => $user,
            'password' => $password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'option' => [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION],
            // 时区设置，避免出现 timestamp 转换错误
            'command' => ["SET time_zone='+8:00'"],
        ]);
        if (!self::isWorkerman()) {
            self::$db = $db;
        }
        return $db;
    }

    public static function isWorkerman()
    {
        return self::$configs['app_server'] === 'workerman';
    }

    public static function getManualLevelBonuses(Medoo $db, int $uid, int $manualLevelId)
    {
        $manualLevel = $db->get('manual_level', '*', ['id' => $manualLevelId]);
        // 获取新境界的奖励
        $bonuses = $db->select('manual_level_bonus', '*', ['manual_level_id' => $manualLevelId]);
        if (empty($bonuses)) {
            return;
        }
        foreach ($bonuses as $bonus) {
            switch ($bonus['type']) {
                // 学习新的技能
                case 2:
                    $db->insert('player_skill', [
                        'uid' => $uid,
                        'skill_id' => $bonus['bonus_id'],
                        'level' => $manualLevel['level'],
                        'manual_id' => $bonus['manual_id'],
                    ]);
                    break;
                default:
                    break;
            }
        }
    }

    public static function operate($db, int $id, Player $player): array
    {
        $operation = $db->get('operation', '*', ['id' => $id]);
        if (empty($operation)) {
            return [];
        }
        // 接受新任务
        if (!empty($operation['new_tasks'])) {
            $taskIds = explode(',', $operation['new_tasks']);
            $acceptedTasks = $db->select('player_task', ['task_id'], [
                'uid' => $player->id,
                'task_id' => $taskIds
            ]);
            foreach ($acceptedTasks as $v) {
                $index = array_search($v['task_id'], $taskIds);
                if ($index !== false) {
                    unset($taskIds[$index]);
                }
            }
            if (!empty($taskIds)) {
                $tasks = \player\getTasksByIds($db, $taskIds);
                $newTasks = [];
                foreach ($tasks as $v) {
                    $newTasks[] = [
                        'uid' => $player->id,
                        'task_id' => $v['id'],
                        'task_info_id' => $v['task_info_id'],
                        'status' => $v['type'] == 3 || $v['type'] == 4 ? 1 : 2,
                    ];
                }
                $db->insert('player_task', $newTasks);
            }
        }

        // 增加操作标识
        if (!empty($operation['inc_identity'])) {
            $condition = [
                'uid' => $player->id,
                'type' => PrivateItem::TYPE_OPERATION_IDENTITY,
                'k' => $operation['inc_identity'],
                'area_id' => $operation['area_id'],
            ];
            $item = $db->get('player_private_items', ['id'], $condition);
            if (empty($item)) {
                $condition['v'] = 1;
                $db->insert('player_private_items', $condition);
            } else {
                $db->update('player_private_items', ['v[+]' => 1], $condition);
            }
        }

        // 失去物品
        if (!empty($operation['lose_items'])) {
            $items = explode(',', $operation['lose_items']);
            foreach ($items as $item) {
                list($itemId, $chance, $amount) = explode('|', $item);
                $random = rand(1, 100);
                if ($random > $chance) {
                    continue;
                }
                $itemInfo = $db->get('item', ['name', 'type'], ['id' => $itemId]);
                $condition = [
                    'uid' => $player->id,
                    'item_id' => $itemId
                ];
                $exists = $db->get('player_item', ['id', 'amount'], $condition);
                if ($exists) {
                    $db->update('player_item', ['amount[-]' => $amount], $condition);
                }
                //Game::flash()->push('message', "你失去{$itemInfo['name']}x$amount");
            }
        }

        // 获得物品
        if (!empty($operation['get_items'])) {
            $items = explode(',', $operation['get_items']);
            foreach ($items as $item) {
                list($itemId, $chance, $amount) = explode('|', $item);
                $random = rand(1, 100);
                if ($random > $chance) {
                    continue;
                }
                $condition = [
                    'uid' => $player->id,
                    'item_id' => $itemId
                ];
                $itemInfo = $db->get('item', ['name', 'type'], ['id' => $itemId]);
                if (empty($itemInfo)) {
                    continue;
                }
                $exists = $db->get('player_item', ['id', 'amount'], $condition);
                if ($itemInfo['type'] != 2 && $exists) {
                    $db->update('player_item', ['amount[+]' => $amount], $condition);
                } else if ($itemInfo['type'] == 2) {
                    $loc = Location::get($db, $player->nowmid);
                    $source = [
                        'location' => $loc->name,
                        'monster' => 'NPC',
                        'player' => $player->name,
                    ];
                    // 增加装备
                    addPlayerEquip($db, $player->id, ['id' => $itemId], $source);
                } else {
                    $subItemId = 0;
                    $condition['sub_item_id'] = $subItemId;
                    $db->insert('player_item', $condition);
                }
                //Game::flash()->push('message', "你获得{$itemInfo['name']}x$amount");
            }
        }

        return $operation;
    }

    public static function lingQiLevel(int $amount)
    {
        if ($amount <= 20) {
            return '<span class="quality-2">灵气稀薄</span>';
        }
        if ($amount <= 40) {
            return '<span class="quality-3">灵气富裕</span>';
        }
        if ($amount <= 60) {
            return '<span class="quality-4">灵气充沛</span>';
        }
        if ($amount <= 80) {
            return '<span class="quality-5">聚灵之地</span>';
        }
        return '<span class="quality-6">灵气源泉</span>';
    }

    /**
     * @param string $key
     * @return string
     */
    public static function littleCamelCase(string $key): string
    {
        return str_replace('_', '', lcfirst(ucwords($key, '_')));
    }

    public static function filterVar($value, string $type = 'STRING')
    {
        if ($value === null) {
            return $value;
        }
        $type = strtoupper($type);
        switch ($type) {
            case 'INT':
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'FLOAT':
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
                break;
            case 'ALPHA':
                $value = preg_replace("/[^A-Za-z0-9]/", '', $value);
                break;
            default:
                $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $value;
    }

    /**
     * @param Location $location
     * @param int $xSize
     * @param int $ySize
     * @return array
     */
    public static function generateMap(Location $location, int $xSize, int $ySize)
    {
        $arr = array_fill(0, $ySize, array_fill(0, $xSize, 0));
        $x = $xSize & 1 ? $xSize / 2 : $xSize / 2 - 1;
        $y = $ySize & 1 ? $ySize / 2 : $ySize / 2 - 1;
        self::recurLoc($location, $x, $y, $arr);

        foreach ($arr as $m => $row) {
            foreach ($row as $n =>$column) {
                if (is_object($column)) {
                    if ($column->up && isset($arr[$m - 1][$n])) {
                        $arr[$m - 1][$n] = '|';
                    }
                    if ($column->down && isset($arr[$m + 1][$n])) {
                        $arr[$m + 1][$n] = '|';
                    }
                    if ($column->left && isset($arr[$m][$n - 1])) {
                        $arr[$m][$n - 1] = '—';
                    }
                    if ($column->right && isset($arr[$m][$n + 1])) {
                        $arr[$m][$n + 1] = '—';
                    }
                }
            }
        }

        return $arr;
    }

    protected static function recurLoc(Location $location, int $x, int $y, &$arr)
    {
        if (!empty($arr[$y][$x]) || !isset($arr[$y][$x])) {
            return;
        }
        $arr[$y][$x] = $location;
        if (!empty($location->up)) {
            if (isset($arr[$y - 2][$x]) && empty($arr[$y - 2][$x])) {
//                $arr[$y - 1][$x] = '|';
                $up = Location::get($location->db, $location->up);
                self::recurLoc($up, $x, $y - 2, $arr);
            }
        }
        if (!empty($location->down)) {
            if (isset($arr[$y + 2][$x]) && empty($arr[$y + 2][$x])) {
//                $arr[$y + 1][$x] = '|';
                $down = Location::get($location->db, $location->down);
                self::recurLoc($down, $x, $y + 2, $arr);
            }
        }
        if (!empty($location->left)) {
            if (isset($arr[$y][$x - 2]) && empty($arr[$y][$x - 2])) {
//                $arr[$y][$x - 1] = '—';
                $left = Location::get($location->db, $location->left);
                self::recurLoc($left, $x - 2, $y, $arr);
            }
        }
        if (!empty($location->right)) {
            if (isset($arr[$y][$x + 2]) && empty($arr[$y][$x + 2])) {
//                $arr[$y][$x + 1] = '—';
                $right = Location::get($location->db, $location->right);
                self::recurLoc($right, $x + 2, $y, $arr);
            }
        }
    }

    public static function isOverloaded(Medoo $db, int $uid, int $storage = 1, int $liftingCapacity = 0)
    {
        $count = $db->count('player_item', [
            'uid' => $uid,
            'storage' => $storage,
            'amount[>]' => 0
        ]);
        if (!$liftingCapacity) {
            switch ($storage) {
                default:
                    $u = $db->get('game1', ['lifting_capacity'], ['id' => $uid]);
                    $liftingCapacity = $u['lifting_capacity'];
            }
        }
        return $count >= $liftingCapacity;
    }

    public static function getPlayerEquipName($playerEquip): string
    {
        $equip = [];
        if (is_array($playerEquip)) {
            $equip['ui_name'] = $playerEquip['ui_name'] ?? $playerEquip['name'];
            $equip['name'] = $playerEquip['name'];
            $equip['shengxing'] = $playerEquip['shengxing'];
            $equip['qianghua'] = $playerEquip['qianghua'];
        } else if (is_object($playerEquip)) {
            $equip['ui_name'] = $playerEquip->uiName ?? $playerEquip->name;
            $equip['name'] = $playerEquip->name;
            $equip['shengxing'] = $playerEquip->shengxing;
            $equip['qianghua'] = $playerEquip->qianghua;
        }
        $name = '';
        if ($equip['shengxing'] > 0) {
            $name .= "{$equip['shengxing']}星.";
        }
        $name .= $equip['ui_name'];
        if ($equip['qianghua'] > 0) {
            $name .= "+{$equip['qianghua']}";
        }
        return $name;
    }

    public static function getVipName($player): string
    {
        $data = [];
        if (is_array($player)) {
            $data['name'] = $player['name'];
            $data['vip'] = $player['vip'];
        } else if (is_object($player)) {
            $data['name'] = $player->name;
            $data['vip'] = $player->vip ?? 0;
        }
        $vipColors = [
            '',
            'color-green',
            'color-red',
            'color-golden'
        ];
        if (!empty($vipColors[$data['vip']])) {
            return sprintf('<span class="%s">%s</span>', $vipColors[$data['vip']], $data['name']);
        }
        
        return $data['name'];
    }

    public static function getQualityColor(int $quality): string
    {
        switch ($quality) {
            case 1:
                return 'color-green';
            case 2:
                return 'color-purple';
            case 3:
                return 'color-red';
            case 4:
                return 'color-golden';
        }
        return '';
    }

    public static function getAllHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $name = str_replace(
                    ' ',
                    '-',
                    ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))
                );
                $headers[$name] = $value;
            } else if ($name == "CONTENT_TYPE") {
                $headers["Content-Type"] = $value;
            } else if ($name == "CONTENT_LENGTH") {
                $headers["Content-Length"] = $value;
            }
        }
        return $headers;
    }
}