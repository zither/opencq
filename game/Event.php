<?php
namespace Xian;

use Medoo\Medoo;

class Event
{
    /**
     * @var Medoo
     */
    protected $db;

    /**
     * @var Session
     */
    protected $session;

    public function __construct(Session $session, Medoo $db)
    {
        $this->session = $session;
        $this->db = $db;
    }

    /**
     * @return array
     */
    public function current(bool $withRawInfo = false): array
    {
        if (!isset($this->session['uid'])) {
            return [null, null, null];
        }
        $date = date('Y-m-d H:i:s', strtotime('-5 seconds'));
        $event = $this->db->get('player_event', '*', [
            'uid' => $this->session['uid'],
            'OR' => [
                'is_temporary' => 0,
                'AND #temporary' => ['is_temporary' => 1, 'created_at[>]' => $date],
            ],
            'ORDER' => ['id' => 'ASC']
        ]);
        if (empty($event)) {
            if ($withRawInfo) {
                return [null, null, null];
            }
            return [null, null];
        }
        parse_str($event['cmd'], $arr);
        $whiteList = [];
        if (!empty($event['white_list'])) {
            $whiteList = explode(',', $event['white_list']);
        }
        if ($withRawInfo) {
            return [$arr, $whiteList, $event];
        }
        return [$arr, $whiteList];
    }

    /**
     * @param int $uid
     * @param string $cmd
     */
    public function set(int $uid, string $cmd, array $whiteList = [], bool $temporary = false)
    {
        $list = '';
        if (!empty($whiteList)) {
            $list = implode(',', $whiteList);
        }
        $this->db->insert('player_event', [
            'uid' => $uid,
            'cmd' => $cmd,
            'white_list' => $list,
            'is_temporary' => $temporary,
        ]);
    }

    /**
     * @return bool
     */
    public function remove(int $uid = 0)
    {
        if (!$uid) {
            if (!isset($this->session['uid'])) {
                return true;
            }
            $uid = $this->session['uid'];
        }
        $this->db->delete('player_event', [
            'uid' => $uid,
            'ORDER' => ['id' => 'ASC'],
            'LIMIT' => 1,
        ]);
        return true;
    }

    public function saveCurrentAction(string $cmd)
    {
        if (empty($this->session['uid'])) {
            return;
        }
        $this->db->insert('cmd_history', ['uid' => $this->session['uid'], 'cmd' => $cmd]);
    }

    public function lastAction()
    {
        if (empty($this->session['uid'])) {
            return "cmd=gomid";
        }
        $cmd = $this->db->get('cmd_history', '*', [
            'uid' => $this->session['uid'],
            'ORDER' => ['id' => 'DESC']
        ]);
        if (empty($cmd)) {
            return "cmd=gomid";
        }
        return $cmd['cmd'];
    }

    public function __destruct()
    {
        unset($this->session, $this->db);
    }
}