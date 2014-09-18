<?php

Namespace Model;

class BaseComposerApp extends BasePHPApp {

  protected $fileSources;

  public function __construct($params) {
    parent::__construct($params);
  }

  public function install($autoPilot = null) {
    if (isset($this->params["hide-title"])) { $this->populateTinyTitle() ; }
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
    // $this->setInstallFlagStatus(true) ; @todo we can deprecate this now as status is dynamic, and install is used by everything not just installers
    if (isset($this->params["hide-completion"])) { $this->populateTinyCompletion(); }
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
    // $this->setInstallFlagStatus(false) ; @todo we can deprecate this now as status is dynamic, and install is used by everything not just installers
    $this->showCompletion();
  }

  // @todo removed hardcoded composer path
  protected function copyComposerJsonToProgramDataFolder() {
      $templatorFactory = new \Model\Templating();
      $templator = $templatorFactory->getModel($this->params);
      $original = "/opt/cleopatra/cleopatra/src/Modules/".$this->getModuleName()."/Templates/composer.json" ;
      $targetLocation = $this->programDataFolder.DIRECTORY_SEPARATOR.$this->programNameMachine.DIRECTORY_SEPARATOR."composer.json" ;
      $templator->template(
          $original,
          array(),
          $targetLocation );
  }

  protected function doComposerCommand(){
      $command = array(
          "cd $this->programDataFolder".DIRECTORY_SEPARATOR.$this->programNameMachine ,
          "wget https://getcomposer.org/installer --no-check-certificate",
          "php installer",
          "sudo php composer.phar install -vvv --prefer-dist"
      );
      self::executeAsShell($command);
    return true;
  }

}