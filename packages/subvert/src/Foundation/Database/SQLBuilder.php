<?php

namespace Subvert\Framework\Foundation\Database;


Class SQLBuilder
{

    protected static $expression = [
        'LIMIT', 'GROUP', 'HAVING', 'ORDER', 'LOCK', 'WHERE'
    ];

    public static function builder($template, array $args = [], array $expression = [])
    {
        $sql = $template['sql'];
        $require = isset($template['require']) ? $template['require'] : [];
        $require = is_array($require) ? $require : [$require];

        static::checkRequire($require, $args);
        
        $sql = static::buildArgs($sql, $args);
        $sql = static::buildExpression($sql, $expression);

        return $sql;
    }

    protected static function escape($args)
    {
        if (is_array($args)) {
            foreach ($args as $key => $value) {
                $args[$key] = static::escape($value);
            }

            return $args;
        }

        return addslashes($args);

        
    }

    protected static function checkRequire($require, $args)
    {
        if (!empty($require)) {
            foreach ($require as $key => $value) {
                if (!isset($args[$value])) {
                    throw new \Exception("必要参数:[{$value}]不存在");
                }
            }
        }
    }

    protected static function buildArgs($sql, $args)
    {
        if (count($args)) {
            foreach ($args as $field => $data) {
                $sql = str_replace('#{'.$field.'}', static::escape($data), $sql);
            }
        }

        return $sql;
    }

    protected static function buildExpression($sql, $expression)
    {
        if (count($expression)) {
            foreach ($expression as $field => $data) {
                $sql = str_replace('#{'.strtoupper($field).'}#', $data, $sql);
            }
        }
        return $sql;
    }



    public static function __callStatic($method, $parameters)
    {
        $instance = new WhereBuilder();

        return call_user_func_array([$instance, $method], $parameters);
    }


}
