<?php

namespace Subvert\Framework\Foundation\Invoker;

use DB;
use Event;
use Subvert\Framework\Contract\Invokable;

class Invoker implements Invokable
{
    const CALL_TYPE_NONE      = -1;
    const CALL_TYPE_DOT       = 1;           // a.b.c.d.e
    const CALL_TYPE_NAMESPACE = 2;           // a/b/c/d@e
    const CALL_TYPE_CALLABLE  = 3;           // function() {}

    static $callStack    = [];

    public static function execute($action, $data)
    {

        $parentCallStack = static::$callStack;
        static::$callStack = [];

        $result = null;
        $except = null;
        $sql = [];

        Event::fire('invoke.before', [$action, $data]);
        $start = microtime(true);

        DB::listen(function($obj) use (&$sql){
            $sql[] = [
                'sql'      => $obj->sql, 
                'bindings' => $obj->bindings, 
                'use'      => $obj->time/1000
            ];
        });


        try {
            $data = static::formatData($data);
            switch (static::checkCallType($action)) {
                case self::CALL_TYPE_DOT:
                    $result = DotInvoker::execute($action, $data);
                    break;
                case self::CALL_TYPE_NAMESPACE:
                    $result = NamespaceInvoker::execute($action, $data);
                    break;
                case self::CALL_TYPE_CALLABLE:
                    $result = CallableInvoker::execute($action, $data);
                    break;
                default:
                    throw new \Exception("Error Invoker Type");
                    break;
            }

        } catch(\Exception $ex) {

            $end = microtime(true);
            $use = number_format(($end - $start), 5);

            $except = (string) $ex;

            Event::fire('invoke.exception', [$action, $data, $ex]);

            static::$callStack = [
                'action'    => $action,
                'data'      => $data,
                'result'    => $result,
                'use'       => $use,
                'sql'       => $sql,
                'exception' => $except,
            ];

            $parentCallStack['children'][] = static::$callStack;
            static::$callStack = $parentCallStack;

            throw $ex;
            

        } catch(\Throwable $ex) {

            $end = microtime(true);
            $use = number_format(($end - $start), 5);

            $except = (string) $ex;

            Event::fire('invoke.exception', [$action, $data, $ex]);

            static::$callStack = [
                'action'    => $action,
                'data'      => $data,
                'result'    => $result,
                'use'       => $use,
                'sql'       => $sql,
                'exception' => $except,
            ];

            $parentCallStack['children'][] = static::$callStack;
            static::$callStack = $parentCallStack;

            throw $ex;
        }

        $end = microtime(true);
        $use = number_format(($end - $start), 5);

        Event::fire('invoke.after', [$action, $data, $result, $except, $use]);

        static::$callStack = [
            'action'    => $action,
            'data'      => $data,
            'result'    => $result,
            'use'       => $use,
            'sql'       => $sql,
            'exception' => $except,
        ];

        $parentCallStack['children'][] = static::$callStack;
        static::$callStack = $parentCallStack;

        return $result;
    }

    public static function checkCallType($action)
    {
        if (false !== strpos($action, '.')) {
            return self::CALL_TYPE_DOT;
        }

        if (false !== strpos($action, '\\') && false !== strpos($action, '@')) {
            return self::CALL_TYPE_NAMESPACE;
        }

        if (is_callable($action)) {
            return self::CALL_TYPE_CALLABLE;
        }

        return self::CALL_TYPE_NONE;
    }

    public static function formatData($data)
    {
        if (is_array($data)) {
            return $data;
        }

        return json_decode($data, true);
    }

}
