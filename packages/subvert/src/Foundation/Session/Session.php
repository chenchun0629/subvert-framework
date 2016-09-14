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
        $this->set('SUBVERT_SESSION_ID', $this->sessionId);
    }

    protected function buildRedisKey($sessionId)
    {
        return 'session:' . app()->appName() . ':' . $sessionId;
    }

    public static function init($sessionId = null)
    {
        return new static($sessionId);
    }

    public static function existsSessionId($sessionId)
    {
        return app('redis')->exists('session:' . app()->appName() . ':' . $sessionId);
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
        return unserialize($this->redis->hget($this->redisKey, $key));
    }
    
    public function set($key, $value)
    {
        $result = $this->redis->hset($this->redisKey, $key, serialize($value));

        $this->redis->expire($this->redisKey, config('session.lifetime'));

        return $result;
    }

    public function all()
    {
        $data = $this->redis->hgetall($this->redisKey);
        foreach ($data as $key => $value) {
            $data[$key] = unserialize($value);
        }
        return $data;
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
