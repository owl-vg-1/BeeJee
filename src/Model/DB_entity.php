<?php

namespace App\Model;

class DB_entity

{
    
    use SettersTrait, PageTrait;

    protected $table_name;
    protected $link;
    protected $page_size = 10;
    protected $default_select = [
        'SELECT' => '*',
        'FROM' => null,
        'WHERE' => 1,
        'GROUP BY' => null,
        'HAVING' => null,
        'ORDER BY' => null,
        'LIMIT' => null
    ];
    protected $current_select = [];
    public $error_list = [];

    function __construct($link, $table_name)
    {
        $this->link = $link;
        $this->table_name = $table_name;
        $this->current_select['FROM'] = $this->table_name;
    }

    function get_sql()
    {
        $sql = '';
        foreach (array_merge($this->default_select, $this->current_select) as $key => $value) {
            if (!empty($value)) {
                $sql .= "$key $value\n";
            }
        }
        return $sql;
    }

    function query()
    {
        $query_result = $this->execute_sql($this->get_sql());

        if ($query_result !== false) {
            return $this->result_query_table($query_result);
        } else {
            return false;
        }
    }

    protected function execute_sql($sql)
    {
        $query_result = $this->link->query($sql);
        if (!empty($this->link->error)) {
            $this->error_list[] = $this->link->error;
        }
        return $query_result;
    }

    protected function result_query_table($query_result)
    {
        $result = [];
        while ($row = $query_result->fetch_assoc()) {
            $result[] = $row;
        }
        return $result;
    }

    function get_fields()
    {
        return array_column($this->result_query_table($this->execute_sql('SHOW COLUMNS FROM ' . $this->table_name)), 'Field');
    }

    function get_field_comments() {
        $com_nam = $this->result_query_table($this->execute_sql("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = '$this->table_name'"));
        return array_combine(array_column($com_nam, 'COLUMN_NAME'), array_column($com_nam, 'COLUMN_COMMENT'));
    }

    function delete($id)
    {
        $this->execute_sql("DELETE FROM `$this->table_name` WHERE id = $id");
        return $this->link->affected_rows;
    }

    function add($add_arr)
    {
        foreach ($add_arr as &$value) {
            if (!empty($value)) {
                $value = "'$value'";
            } else {
                $value = "NULL";
            }
        }
        $this->execute_sql("INSERT INTO `$this->table_name`(" . implode(',', array_keys($add_arr)) . ") VALUES (" . implode(", ", $add_arr) . ")");
        return $this->link->insert_id;
    }

    function row_count()
    {
        return $this->result_query_table($this->execute_sql("SELECT COUNT(*) AS C FROM $this->table_name"))[0]['C'];
    }

    function clear_table()
    {
        $this->execute_sql("DELETE FROM `$this->table_name`");
        return $this->link->affected_rows;
    }

    function drop_table()
    {
        $this->execute_sql("DROP TABLE `$this->table_name`");
        return $this->link->affected_rows;
    }

    function edit($id, $arr)
    {
        $new_arr = [];
        foreach ($arr as $key => $value) {
            $new_arr[] = "$key='$value'";
        }

        $this->execute_sql("UPDATE `$this->table_name` SET " . implode(', ', $new_arr) . " WHERE id = $id");
    }

    function array_fields_filter($arr_fields)
    {
        return array_intersect_key($arr_fields, array_flip($this->get_fields()));
    }

    function get_row_by_id($id)
    { 
        $arr = $this->add_where_condition("id=$id")->query()[0];
        unset($arr['id']);
        return $arr;
    }

}