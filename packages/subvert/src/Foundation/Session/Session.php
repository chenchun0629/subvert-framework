<?php

namespace Subvert\Framework\Foundation\Session;

use ArrayAccess;
use Subvert\Framework\Contract\Sessionable;

class Session implements Sessionable, ArrayAccess
{

    protected $sessionId;
    protected $redis;
    protected $redisKey;

    public function __construct($sessionId = null)
    {
        if (empty($sessionId)) {
            $sessionId = $this->sessionId();
        }

        $this->sessionId = $sessionId;
        $this->redis = app('redis');
        $this->redisKey = $this->buildRedisKey($this->sessionId);
    }

    protected function buildRedisKey($sessionId)
    {
        return 'session:' . app()->appName() . ':' . $sessionId;
    }

    public static function init($sessionId = null)
    {
        return new static($sessionId);
    }

    public function sessionId()
    {
        return empty($this->sessionId) ? $this->buildSessionId() : $this->sessionId;
    }

    public function buildSessionId()
    {
        $time = microtime(true);
        $rand1 = rand(100000, 999999);
        $rand2 = rand($rand1, $time);
        return md5( $time . $rand1 . $rand2 );
    }


    public function get($key) 
    {
        return $this->redis->hget($this->redisKey, $key);
    }
    
    public function set($key, $value)
    {
        $result = $this->redis->hset($this->redisKey, $key, $value);

        $this->redis->expire($this->redisKey, config('session.lifetime'));

        return $result;
    }

    public function all()
    {
        return $this->redis->hgetall($this->redisKey);
    }

    public function delete($key)
    {
        return $this->redis->hdel($this->redisKey, $key);
    }

    public function destory()
    {
        $this->redis->del($this->redisKey);
    }

    public function offsetExists($offset)
    {
        return $this->redis->hexists($this->redisKey, $offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->hdel($this->redisKey, $offset);
    }

}
