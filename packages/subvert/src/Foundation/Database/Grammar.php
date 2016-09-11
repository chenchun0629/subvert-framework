<?php

namespace Subvert\Framework\Foundation\Database;

use Illuminate\Database\Grammar as BaseGrammar;

Class Grammar extends BaseGrammar
{

    /**
     * Compile the "where" portions of the query.
     *
     * @return string
     */
    public function compileWheres($query)
    {
        $sql = [];

        if (is_null($query->wheres)) {
            return '';
        }

        // Each type of where clauses has its own compiler function which is responsible
        // for actually creating the where clauses SQL. This helps keep the code nice
        // and maintainable since each clause has a very small method that it uses.
        foreach ($query->wheres as $where) {
            $method = "where{$where['type']}";

            $sql[] = $where['boolean'].' '.$this->$method($query, $where);
        }

        // If we actually have some where clauses, we will strip off the first boolean
        // operator, which is added by the query builders for convenience so we can
        // avoid checking for the first clauses in each of the compilers methods.
        if (count($sql) > 0) {
            $sql = implode(' ', $sql);

            return 'WHERE ' . $this->removeLeadingBoolean($sql);
        }

        return '';
    }

    public function parameter($value)
    {
        return is_numeric($value) ? $value : "'" . addslashes($value) . "'";
    }

    public function wrap($value, $prefixAlias = false)
    {
        if (strpos($value, '.') !== false) {
            $segments = explode('.', $value);

            return $this->wrap($segments[0]).'.'.$this->wrap($segments[1]);
        }

        return '`' . $value . '`';
    }

    /**
     * Remove the leading boolean from a statement.
     *
     * @param  string  $value
     * @return string
     */
    protected function removeLeadingBoolean($value)
    {
        return preg_replace('/and |or /i', '', $value, 1);
    }

    /**
     * Compile a basic where clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereBasic($query, $where)
    {
        $value = $this->parameter($where['value']);

        return $this->wrap($where['column']).' '.$where['operator'].' '.$value;
    }

    protected function whereNested($query, $where)
    {
        $nested = $where['query'];

        return '('.substr($this->compileWheres($nested), 6).')';
    }

    /**
     * Compile a where clause comparing two columns..
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereColumn($query, $where)
    {
        $second = $this->wrap($where['second']);

        return $this->wrap($where['first']).' '.$where['operator'].' '.$second;
    }

    /**
     * Compile a "between" where clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereBetween($query, $where)
    {
        $between = $where['not'] ? 'not between' : 'between';

        return $this->wrap($where['column']).' '.$between.' ' . $this->parameter(reset($where['values'])) . ' and ' . $this->parameter(end($where['values']));
    }

    /**
     * Compile a "where in" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereIn($query, $where)
    {
        if (empty($where['values'])) {
            return '0 = 1';
        }

        $values = $this->parameterize($where['values']);

        return $this->wrap($where['column']).' in ('.$values.')';
    }


    /**
     * Compile a "where not in" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNotIn($query, $where)
    {
        if (empty($where['values'])) {
            return '1 = 1';
        }

        $values = $this->parameterize($where['values']);

        return $this->wrap($where['column']).' not in ('.$values.')';
    }


    /**
     * Compile a "where null" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNull($query, $where)
    {
        return $this->wrap($where['column']).' is null';
    }

    /**
     * Compile a "where not null" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNotNull($query, $where)
    {
        return $this->wrap($where['column']).' is not null';
    }


    /**
     * Compile a raw where clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereRaw($query, $where)
    {
        return $where['sql'];
    }




}
