<?php

namespace Xian;

class Collection implements \ArrayAccess, \Countable, \IteratorAggregate 
{
    /**
     * @var array
     */
    protected $data = [];

    public function has($key) 
    {
        return isset($this->data[$key]);
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function set($key, $value) 
    {
        $this->data[$key] = $value;
    } 

    public function remove($key) 
    {
        unset($this->data[$key]); 
    }

    public function all()
    {
        return $this->data;
    }

    public function clear()
    {
        $this->data = [];
    }

    public function offsetExists($key)
    {
        return $this->has($key);
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value); 
    }

    public function offsetUnset($key) 
    {
        return $this->remove($key);
    }

    public function count()
    {
        return count($this->data);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function __destruct()
    {
        unset($this->data);
    }
}
