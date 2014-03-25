<?php

Namespace Model;

class BaseComposerApp extends BasePHPApp {

  protected $fileSources;

  public function __construct($params) {
    parent::__construct($params);
  }

  public function install($autoPilot = null) {
    $this->showTitle();
    $this->programDataFolder = ($autoPilot)
      ? $autoPilot->{$this->autopilotDefiner."InstallDirectory"}
      : $this->askForProgramDataFolder();
    $this->programExecutorFolder = ($autoPilot)
      ? $autoPilot->{$this->autopilotDefiner."ExecutorDirectory"}
      : $this->askForProgramExecutorFolder();
    $this->deleteProgramDataFolderAsRootIfExists();
    $this->makeProgramDataFolderIfNeeded();
    $this->copyComposerJsonToProgramDataFolder();
    $this->doComposerCommand();
    $this->deleteExecutorIfExists();
    $this->populateExecutorFile();
    $this->saveExecutorFile();
    $this->deleteInstallationFiles();
    $this->changePermissions();
    $this->setInstallFlagStatus(true) ;
    $this->showCompletion();
  }

  public function unInstall($autoPilot = null) {
    $this->showTitle();
    $this->programDataFolder = ($autoPilot)
      ? $autoPilot->{$this->autopilotDefiner}
      : $this->askForProgramDataFolder();
    $this->programExecutorFolder = $this->askForProgramExecutorFolder();
    $this->deleteProgramDataFolderAsRootIfExists();
    $this->deleteExecutorIfExists();
    $this->setInstallFlagStatus(false) ;
    $this->showCompletion();
  }

  protected function copyComposerJsonToProgramDataFolder($original = null) {
      $templatorFactory = new \Model\Templating();
      $templator = $templatorFactory->getModel($this->params);
      $targetLocation = $this->programDataFolder.DIRECTORY_SEPARATOR.$this->programNameMachine.DIRECTORY_SEPARATOR."composer.json" ;
      $templator->template(
          $original,
          array(),
          $targetLocation );
  }

  protected function doComposerCommand(){
      $command = array(
          "cd $this->programDataFolder".DIRECTORY_SEPARATOR.$this->programNameMachine ,
          "curl -s http://getcomposer.org/installer | php",
          "php composer.phar install"
      );
      self::executeAsShell($command);
    return true;
  }

}