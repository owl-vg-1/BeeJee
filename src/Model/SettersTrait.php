<?php
//+++
namespace App\Model;

trait SettersTrait {

    function add_where_condition($add_query)
    {
        $this->current_select['WHERE'] = !empty($this->current_select['WHERE']) ? $this->current_select['WHERE'] . " AND $add_query" : $add_query;
        return $this;
    }

    function reset_where_condition()
    {
        unset($this->current_select['WHERE']);
        return $this;
    }

    function add_order_by_asc($str)
    {
        $this->current_select['ORDER BY'] = !empty($this->current_select['ORDER BY']) ? $this->current_select['ORDER BY'] . ", $str" : $str;
        return $this;
    }

    function add_order_by_desc($str)
    {
        $this->current_select['ORDER BY'] = !empty($this->current_select['ORDER BY']) ? $this->current_select['ORDER BY'] . ", $str DESC" : $str . " DESC";
        return $this;
    }

    function reset_order_by()
    {
        unset($this->current_select['ORDER BY']);
        return $this;
    }

    function reset_select()
    {
        unset($this->current_select['SELECT']);
        return $this;
    }

    function add_select_field($str)
    {
        $this->current_select['SELECT'] = !empty($this->current_select['SELECT']) ? $this->current_select['SELECT'] . ", $str" : $str;
        return $this;
    }

    function add_group_by($str)
    {
        $this->current_select['GROUP BY'] = !empty($this->current_select['GROUP BY']) ? $this->current_select['GROUP BY'] . ", $str" : $str;
        return $this;
    }

    function reset_group_by()
    {
        unset($this->current_select['GROUP BY']);
        return $this;
    }

    function add_having_condition($add_query)
    {
        $this->current_select['HAVING'] = !empty($this->current_select['HAVING']) ? $this->current_select['HAVING'] . " AND $add_query" : $add_query;
        return $this;
    }

    function reset_having_condition()
    {
        unset($this->current_select['HAVING']);
        return $this;
    }

    function reset_default_select()
    {
        $this->current_select = [];
        $this->current_select['FROM'] = $this->table_name;
        return $this;
    }

}