<?php

namespace Subvert\Framework\Foundation\Invoker;

use DB;
use Event;
use Subvert\Framework\Contract\Invokable;

class Invoker implements Invokable
{
    const CALL_TYPE_NONE        = -1;
    const CALL_TYPE_DOT         = 1;           // a.b.c.d.e
    const CALL_TYPE_NAMESPACE   = 2;           // a/b/c/d@e
    const CALL_TYPE_CALLABLE    = 3;           // function() {}

    static $callStack   = [];
    static $sql         = [];
    static $count       = 0;
    static $isBindEvent = false;

    public static function execute($action, $data)
    {

        static::bindEvent();

        static::$count++;

        // echo "=============", static::$count, "=============\n";
        
        $parentCallStack = static::$callStack;
        static::$callStack = [];

        $sql = static::$sql;

        $result = null;
        $except = null;

        Event::fire('invoke.before', [$action, $data]);
        $start = microtime(true);

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

            $childStack = static::$callStack;

            static::$callStack = [
                'action'    => $action,
                'data'      => $data,
                'result'    => $result,
                'use'       => $use,
                'sql'       => static::$sql,
                'exception' => $except,
                'children'  => $childStack
            ];

            $parentCallStack[] = static::$callStack;
            static::$callStack = $parentCallStack;

            static::$sql = $sql;

            static::$count--;

            throw $ex;
        } catch(\Throwable $ex) {

            $end = microtime(true);
            $use = number_format(($end - $start), 5);

            $except = (string) $ex;

            Event::fire('invoke.exception', [$action, $data, $ex]);

            $childStack = static::$callStack;

            static::$callStack = [
                'action'    => $action,
                'data'      => $data,
                'result'    => $result,
                'use'       => $use,
                'sql'       => static::$sql,
                'exception' => $except,
                'children'  => $childStack
            ];

            $parentCallStack[] = static::$callStack;
            static::$callStack = $parentCallStack;

            static::$sql = $sql;

            static::$count--;

            throw $ex;
        }

        $end = microtime(true);
        $use = number_format(($end - $start), 5);

        Event::fire('invoke.after', [$action, $data, $result, $except, $use]);

        $childStack = static::$callStack;

        static::$callStack = [
            'action'    => $action,
            'data'      => $data,
            'result'    => $result,
            'use'       => $use,
            'sql'       => static::$sql,
            'exception' => $except,
            'children'  => $childStack
        ];

        $parentCallStack[] = static::$callStack;
        static::$callStack = $parentCallStack;

        static::$sql = $sql;

        // echo "=============", static::$count, "=============\n";
        static::$count--;

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

        $json = json_decode($data, true);

        if ($json) {
            return $json;
        }

        return (array) $data;
    }

    protected static function bindEvent()
    {
        if (!static::$isBindEvent) {
            DB::listen(function($obj) {
                static::$sql[] = [
                    'sql'      => $obj->sql, 
                    'bindings' => $obj->bindings, 
                    'use'      => number_format(($obj->time/1000), 5),
                ];
            });
            static::$isBindEvent = true;
        }
    }

}
