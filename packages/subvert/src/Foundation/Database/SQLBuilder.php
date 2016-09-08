<?php

namespace Subvert\Framework\Foundation\Database;


Class SQLBuilder
{

    public static function builder($template, array $args = [], $limit = null, $order = null)
    {
        $sql = $template['sql'];
        $require = isset($template['require']) ? $template['require'] : [];
        $require = is_array($require) ? $require : [$require];

        static::checkRequire($require, $args);
        
        $sql = static::buildArgs($sql, $args);
        $sql = static::buildLimit($sql, $limit);
        $sql = static::buildOrder($sql, $order);

        return $sql;
    }

    protected static function escape($args)
    {
        foreach ($args as $key => $value) {
            $args[$key] = mysql_real_escape_string($value);
        }

        return $args;
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
                $sql = str_replace('#{'.$field.'}', $data, $sql);
            }
        }

        return $sql;
    }

    protected static function buildLimit($sql, $limit)
    {
        if ($limit) {
            $sql = str_replace('#{LIMIT}#', $limit, $sql);
        }

        return $sql;
    }

    protected static function buildOrder($sql, $order)
    {
        if ($order) {
            $sql = str_replace('#{ORDER}#', $order, $sql);
        }

        return $sql;
    }


}