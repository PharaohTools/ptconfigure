<?php

namespace Model;

class RegExp {

    public $regexp;

    function __construct($regexp)
    {
        $this->regexp = $regexp;
    }

    function __toString()
    {
        return "RegExp({$this->regexp})";
    }


}