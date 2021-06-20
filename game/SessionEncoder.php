<?php

namespace Xian;

class SessionEncoder implements EncoderInterface
{
    /**
     * @var Session
     */
    protected $session;

    const CMD_NEXT_STORAGE_KEY = 'cmd_next_storage';
    const CMD_CURRENT_STORAGE_KEY = 'cmd_current_storage';
    const CMD_MAX_KEY = 'cmd_max';

    public function __construct(Session $session)
    {
        $this->session = $session;
        if (!isset($this->session[self::CMD_NEXT_STORAGE_KEY])) {
            $this->session[self::CMD_NEXT_STORAGE_KEY] = [];
        }
        $this->session[self::CMD_CURRENT_STORAGE_KEY] = $this->session[self::CMD_NEXT_STORAGE_KEY];
        $this->session[self::CMD_NEXT_STORAGE_KEY] = [];
        if (!isset($this->session[self::CMD_MAX_KEY])) {
            $this->session[self::CMD_MAX_KEY] = 1;
        }
    }

    protected function getMaxCmd()
    {
        if ($this->session[self::CMD_MAX_KEY] >= 999) {
            $this->session[self::CMD_MAX_KEY] = 1;
        }
        $max = $this->session[self::CMD_MAX_KEY];
        $this->session[self::CMD_MAX_KEY] = $max + 1;
        return $max;
    }

    /**
     * @param string $string
     * @return string
     */
    public function encode(string $string): string
    {
        $cmd = $this->getMaxCmd();
        $storage = $this->session[self::CMD_NEXT_STORAGE_KEY];
        $storage[$cmd] = $string;
        $this->session[self::CMD_NEXT_STORAGE_KEY] = $storage;
        return $cmd;
    }

    /**
     * @param string $string
     * @return string
     */
    public function decode(string $string): string
    {
        $cmd = (int)$string;
        if (!isset($this->session[self::CMD_CURRENT_STORAGE_KEY][$cmd])) {
            return 'cmd=gomid';
        }
        return $this->session[self::CMD_CURRENT_STORAGE_KEY][$cmd];
    }

    /**
     * 释放资源
     */
    public function __destruct()
    {
        unset($this->session);
    }
}