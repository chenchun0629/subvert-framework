<?php

namespace Subvert\Framework\Foundation\Response;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

use Subvert\Framework\Traits\JsonableTrait;
use Subvert\Framework\Traits\ArrayAccessTrait;
use Subvert\Framework\Traits\JsonSerializableTrait;

class ResponseData implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{

    use ArrayAccessTrait, JsonableTrait, JsonSerializableTrait;

    const FLAG_SUCCESS = 'success';
    const FLAG_NOTICE  = 'notice';
    const FLAG_FAIL    = 'fail';

    protected $code;

    protected $message;

    protected $response;

    protected $flag;

    public function __construct(array $message, $response = [])
    {
        $this->code     = $message['code'];
        $this->message  = $message['message'];
        $this->flag     = $message['flag'];
        $this->response = isset($message['response']) ? $message['response'] : $response;
    }

    public static function set($message, $response = [])
    {
        if ($message instanceof ResponseData) {
            return $message;
        }
        return new static((array)$message, $response);
    }

    public static function success($response = [])
    {
        if ($response instanceof ResponseData) {
            return $response;
        }
        return new static(FrameworkCode::SYSTEM_SUCCESS, $response);
    }
    
    public function toArray()
    {
        return [
            'code'     => $this->code,
            'message'  => $this->message,
            'response' => $this->response,
            'flag'     => $this->flag,
        ];
    }
    
    public function __toString()
    {
        return $this->toJson();
    }

}
