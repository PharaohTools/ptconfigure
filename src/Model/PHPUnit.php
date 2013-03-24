<?php

Namespace Model;

class PHPUnit extends BasePHPApp {


  public function __construct() {

    $this->fileSource = "http://github.com/phpengine/boxboss-source-phpunit";
    $this->tempDir = '/tmp';
    $this->programNameMachine = "phpunit"; // command to be used on command line
    $this->programNameFriendly = "GC Box Boss!"; // 12 chars
    parent::__construct();
  }

}