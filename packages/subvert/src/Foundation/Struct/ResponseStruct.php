<?php

namespace Subvert\Framework\Foundation\Struct;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

use Subvert\Framework\Traits\JsonableTrait;
use Subvert\Framework\Traits\ArrayAccessTrait;
use Subvert\Framework\Traits\JsonSerializableTrait;

class ResponseStruct implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{

    use ArrayAccessTrait, JsonableTrait, JsonSerializableTrait;

    const FLAG_SUCCESS = 0;
    const FLAG_NOTICE  = 1;
    const FLAG_FAIL    = 2;

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

    public function toArray()
    {
        return [
            'code'     => $this->code,
            'message'  => $this->message,
            'response' => $this->response,
            'flag'     => $this->flag,
        ];
    }

    public static function set(array $message, $response = [])
    {
        return new static($message, $response);
    }

}
