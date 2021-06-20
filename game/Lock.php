<?php
namespace Xian;

class Lock
{
    /**
     * @var Session
     */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function acquire()
    {
        $releaseTime = empty($this->session['vip']) ? 0.7 : 0.5;
        $now = microtime(true);
        if (empty($this->session['lock']) || ($now + $this->session['lock'] >= $releaseTime)) {
            $this->session['lock'] = -$now;
            return false;
        }
        return true;
    }

    public function release()
    {
        $this->session->remove('lock');
    }

    public function __destruct()
    {
        unset($this->session);
    }
}