<?php

Namespace Model;

class BaseComposerApp extends BasePHPApp {

    protected $fileSources;

    public function __construct($params) {
        parent::__construct($params);
    }

    public function install($autoPilot = null) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["hide-title"])) { $this->populateTinyTitle() ; }
        $this->showTitle();
        if ($this->hookInstExists("pre")) {
            $logging->log("Executing Pre Install Commands", $this->getModuleName()) ;
            $this->doInstallCommand("pre") ; }
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
        $this->changeSourcePermissions();
        $this->changeExecutorPermissions();
        if ($this->hookInstExists("post")) {
            $logging->log("Executing Post Install Commands", $this->getModuleName()) ;
            $this->doInstallCommand("post") ;  }
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
        $original = PFILESDIR."ptconfigure".DS."ptconfigure".DS."src".DS."Modules".DS.$this->getModuleName().DS."Templates".DS."composer.json" ;
        $targetLocation = $this->programDataFolder.DS.$this->programNameMachine.DS."composer.json" ;
        $templator->template(
            $original,
            array(),
            $targetLocation );
    }

    protected function doComposerCommand() {
        $targetLocation = $this->programDataFolder.DS.$this->programNameMachine.DS ;
        $composerFactory = new \Model\Composer();
        $composer = $composerFactory->getModel($this->params);
        if ($composer->askStatus() !== true) {
            $composer->install() ; }
        $command = array(
            "cd $targetLocation && composer install -q --prefer-dist" );
        $res = self::executeAndGetReturnCode($command, true, true);
        return ($res["rc"]==0) ? true : false ;
    }

}