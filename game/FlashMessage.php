<?php 
namespace Xian;

use RuntimeException;

class FlashMessage
{
    /**
     * Storage key
     *
     * @var string
     */
    protected $storageKey = "MemoFlashMessages";

    /**
     * @var Session
     */
    protected $session;

    /**
     * Constructor
     *
     * @throws \RuntimeException
     */
    public function __construct(Session $session)
    {
        if (!$session->isStarted()) {
            throw new RuntimeException("Session is not started");
        }
        $this->session = $session;
        $this->initStorage();
    }

    /**
     * Init message storage
     */
    protected function initStorage()
    {
        if (!isset($this->session[$this->storageKey])) {
            $this->session[$this->storageKey] = [
                'fromPrevious' => [],
                'forNext' => [],
            ];
        } else {
            $storage = $this->session[$this->storageKey];
            $storage["fromPrevious"] = [];
            if (isset($storage["forNext"]) && is_array($storage["forNext"])) {
                $storage["fromPrevious"] = $storage["forNext"];
            }
            $storage["forNext"] = [];
            $this->session[$this->storageKey] = $storage;
        }
    }

    /**
     * Get flash messages
     *
     * @param string $key
     * @param null $default
     * @return null | array
     */
    public function get($key, $default = null)
    {
        if (!isset($this->session[$this->storageKey]["fromPrevious"][$key])) {
            return $default;
        }
        return $this->session[$this->storageKey]["fromPrevious"][$key];
    }

    /**
     * Set flash message
     *
     * @param string $key
     * @param string $message
     */
    public function set($key, $message)
    {
        if (is_string($message) || method_exists($message, "__toString")) {
            $storage = $this->session[$this->storageKey];
            $storage["forNext"][$key] = [$message];
            $this->session[$this->storageKey] = $storage;
        }
    }

    protected function store(array $data)
    {
        $this->session[$this->storageKey] = $data;
    }

    protected function getStorage()
    {
        return $this->session[$this->storageKey] ?? [];
    }

    /**
     * @param $key
     * @param $message
     */
    public function push($key, $message)
    {
        if (is_string($message) || method_exists($message, "__toString")) {
            $storage = $this->getStorage();
            if (!isset($storage["forNext"][$key])) {
                $storage["forNext"][$key] = [];
            }
            $storage["forNext"][$key][] = $message;
            $this->store($storage);
        }
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function pop($key, $default = null)
    {
        if (!isset($this->session[$this->storageKey]["fromPrevious"][$key])) {
            return $default;
        }
        $storage = $this->getStorage();
        $return = array_pop($storage['fromPrevious'][$key]);
        $this->store($storage);
        return $return;
    }

    /**
     * @param $key
     * @param $message
     */
    public function now($key, $message)
    {
        if (is_string($message) || method_exists($message, "__toString")) {
            $storage = $this->getStorage();
            $storage["fromPrevious"][$key][] = $message;
            $this->store($storage);
        }
    }

    /**
     * Does the storage have a given key
     *
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->session[$this->storageKey]["fromPrevious"][$key]);
    }

    /**
     * @param $message
     */
    public function error($message)
    {
        $this->push('error', $message);
    }

    /**
     * @param $message
     */
    public function success($message)
    {
        $this->push('success', $message);
    }

    public function __destruct()
    {
        unset($this->session);
    }
}
