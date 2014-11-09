<?php

Namespace Model;

class BaseWindowsApp extends BaseLinuxApp {

    public $defaultStatusCommandPrefix = "where.exe";

    public function __construct($params) {
        parent::__construct($params);
    }

}