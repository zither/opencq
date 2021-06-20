<?php
namespace Xian;

use Lua;
use Medoo\Medoo;

/**
 * Class Condition
 * @package Xian
 * @method withUid(int $uid)
 * @method validate(array $condition): mixed
 * @method execute(string $code): mixed
 */
class Condition
{
    /**
     * @var Medoo
     */
    protected $db;

    /**
     * @var Matcher
     */
    protected $matcher;

    /**
     * @var Lua
     */
    protected $lua;

    /**
     * Condition constructor.
     * @param Medoo $db
     * @param int $uid
     */
    public function __construct(Medoo $db, int $uid = 0)
    {
        $this->db  = $db;
        $this->lua = new Lua();
        $this->matcher = new Matcher($this->db, $this->lua, $uid);
    }

    public function __call(string $method, array $args)
    {
        if (method_exists($this->matcher, $method)) {
            return call_user_func_array([$this->matcher, $method], $args);
        }
        throw new \BadMethodCallException();
    }

    /**
     * @Notice 这里必须手动释放 lua 和 matcher
     */
    public function __destruct()
    {
        $this->matcher->__destruct();
        unset($this->db, $this->lua, $this->matcher);
    }
}