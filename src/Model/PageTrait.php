<?php

namespace App\Model;

trait PageTrait {

    function set_page($page)
    {
        $this->current_select['LIMIT'] = $page * $this->page_size . ", " . $this->page_size;
        return $this;
    }

    function set_page_size($size)
    {
        $this->page_size = $size;
        return $this;
    }

    function reset_page()
    {
        unset($this->current_select['LIMIT']);
        return $this;
    }

    function page_count()
    {
        return ceil($this->row_count() / $this->page_size);
    }

}