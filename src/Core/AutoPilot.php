<?php

Namespace Core ;

class AutoPilot {

  // Git, gitk,git-cola and git-core
  public $GitToolsInstallExecute = false; // true or false
  public $GitToolsUnInstallExecute = false; // true or false

  // Devhelper Install Variables
  public $DevhelperInstallExecute = false; // true or false
  public $DevhelperUnInstallExecute = false;
  public $DevhelperInstallDirectory = '/opt/devhelper';
  public $DevhelperExecutorDirectory = '/usr/bin';

  // PHP Unit 3.5 (for php 5.3) Install Variables
  public $PHPUnit35InstallExecute = false; // true or false
  public $PHPUnit35UnInstallExecute = false;
  public $PHPUnit35InstallDirectory = '/opt/phpunit';
  public $PHPUnit35ExecutorDirectory = '/usr/bin';

  // PHP Unit 3.5 (for php 5.3) Install Variables
  public $PHPCSInstallExecute = false; // true or false
  public $PHPCSUnInstallExecute = false;
  public $PHPCSInstallDirectory = '/opt/phpcs';
  public $PHPCSExecutorDirectory = '/usr/bin';

  // PHP Unit 3.5 (for php 5.3) Install Variables
  public $PHPMDInstallExecute = false; // true or false
  public $PHPMDUnInstallExecute = false;
  public $PHPMDInstallDirectory = '/opt/phpmd';
  public $PHPMDExecutorDirectory = '/usr/bin';

  // Selenium Server
  public $SeleniumServerInstallExecute = false; // true or false
  public $SeleniumServerUnInstallExecute = false;

}
