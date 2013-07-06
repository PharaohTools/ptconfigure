<?php

Namespace Controller ;

class Install extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action=="cli") {

            $sshModel = new \Model\InvokeSSH();
            $this->content["invokeSshScriptResult"] = $sshModel->askWhetherToInvokeSSHScript();
            $this->content["invokeSshShellResult"] = $sshModel->askWhetherToInvokeSSHShell();

            $projectModel = new \Model\Project();
            $this->content["projectContInitResult"] = $projectModel->askWhetherToInitializeProjectContainer();

            $gitCheckoutModel = new \Model\CheckoutGit();
            $this->content["checkoutResult"] = $gitCheckoutModel->checkoutProject();

            $projectModel = new \Model\Project();
            $this->content["projectInitResult"] = $projectModel->askWhetherToInitializeProject();
            $this->content["projectBuildResult"] = $projectModel->askWhetherToInstallBuildInProject();

            $hostEditorModel = new \Model\HostEditor();
            $this->content["hostEditorResult"] = $hostEditorModel->askWhetherToDoHostEntry();

            $VHostEditorModel = new \Model\VHostEditor();
            $this->content["VhostEditorResult"] = $VHostEditorModel->askWhetherToCreateVHost();

            $dbConfigureModel = new \Model\DBConfigure();
            $this->content["dbResetResult"] = $dbConfigureModel->askWhetherToResetDBConfiguration();
            $this->content["dbConfigureResult"] = $dbConfigureModel->askWhetherToConfigureDB();

            $dbInstallModel = new \Model\DBInstall();
            $this->content["dbInstallResult"] = $dbInstallModel->askWhetherToInstallDB($dbConfigureModel);

            $cukeConfModel = new \Model\CukeConf();
            $this->content["cukeCreateResult"] = $cukeConfModel->askWhetherToCreateCuke();
            $this->content["cukeResetResult"] = $cukeConfModel->askWhetherToResetCuke();

            $versionModel = new \Model\Version();
            $this->content["versioningResult"] = $versionModel->askWhetherToVersionSpecific();

            return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content); }

        if ($action=="autopilot") {

            $autoPilotParam= (isset($pageVars["route"]["extraParams"][0])) ? $pageVars["route"]["extraParams"][0] : null;

            if (isset($autoPilotParam) && strlen($autoPilotParam)>0 ) {

                $autoPilot = $this->loadAutoPilot($autoPilotParam);

                if ( $autoPilot!==null ) {

                    // ssh data/script invoke
                    $sshModel = new \Model\InvokeSSH();
                    $this->content["sshInvokeDataResult"] = $sshModel->runAutoPilotInvokeSSHData($autoPilot);
                    if ($autoPilot->sshInvokeSSHDataExecute && $this->content["sshInvokeDataResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot SSH Invoke Data Setup Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    $this->content["sshInvokeScriptResult"] = $sshModel->runAutoPilotInvokeSSHScript($autoPilot);
                    if ($autoPilot->sshInvokeSSHScriptExecute && $this->content["sshInvokeScriptResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot SSH Invoke Script Setup Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    // project
                    $projectModel = new \Model\Project();
                    $this->content["projectContainerResult"] = $projectModel->runAutoPilotProjectContInit($autoPilot);
                    if ($autoPilot->projectContainerInitExecute && $this->content["projectContainerResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Project Container Setup Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    // git checkout
                    $gitCheckoutModel = new \Model\CheckoutGit();
                    $this->content["gitDeletorResult"] = $gitCheckoutModel->runAutoPilotDeletor($autoPilot);
                    if ($autoPilot->gitDeletorExecute && $this->content["gitDeletorResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Deletor Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    $this->content["gitCheckoutResult"] = $gitCheckoutModel->runAutoPilotCloner($autoPilot);
                    if ($autoPilot->gitCheckoutExecute && $this->content["gitCheckoutResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Checkout/Clone Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    // project
                    $projectModel = new \Model\Project();
                    $this->content["projectInitResult"] = $projectModel->runAutoPilotInit($autoPilot);
                    if ($autoPilot->projectInitializeExecute && $this->content["projectInitResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Project Initialize Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    $this->content["projectBuildResult"] = $projectModel->runAutoPilotBuildInstall($autoPilot);
                    if ($autoPilot->projectBuildInstallExecute && $this->content["projectBuildResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Build Install Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    // host editor
                    $hostEditorModel = new \Model\HostEditor();
                    $this->content["hostEditorAdditionResult"] = $hostEditorModel->runAutoPilotHostAddition($autoPilot);
                    if ($autoPilot->hostEditorAdditionExecute && $this->content["hostEditorAdditionResult"] != "1") {
                        $this->content["autoPilotErrors"]="Host file editor creation Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    $this->content["hostEditorDeletionResult"] = $hostEditorModel->runAutoPilotHostDeletion($autoPilot);
                    if ($autoPilot->hostEditorDeletionExecute && $this->content["hostEditorDeletionResult"] != "1") {
                        $this->content["autoPilotErrors"]="Host file editor deletion Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    // DB Configure
                    $dbConfigureModel = new \Model\DBConfigure() ;
                    $this->content["dbResetResult"] = $dbConfigureModel->runAutoPilotDBReset($autoPilot);
                    if ($autoPilot->dbResetExecute && $this->content["dbResetResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot DB Reset Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    $dbConfigureModel = new \Model\DBConfigure() ;
                    $this->content["dbConfigureResult"] = $dbConfigureModel->runAutoPilotDBConfiguration($autoPilot);
                    if ($autoPilot->dbConfigureExecute && $this->content["dbConfigureResult"] != "1" ) {
                        $this->content["autoPilotErrors"]="Auto Pilot DB Configure Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    // DB Install
                    $dbInstallModel = new \Model\DBInstall();
                    $this->content["dbDropResult"] = $dbInstallModel->runAutoPilotDBRemoval($autoPilot);
                    if ($autoPilot->dbDropExecute && $this->content["dbDropResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot DB Reset Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }
                    $this->content["dbInstallResult"] = $dbInstallModel->runAutoPilotDBInstallation($autoPilot);
                    if ($autoPilot->dbInstallExecute && $this->content["dbInstallResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot DB Install Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    // Cuke Conf
                    $cukeConfModel = new \Model\CukeConf();
                    $this->content["cukeConfDeletionResult"] = $cukeConfModel->runAutoPilotDeletion($autoPilot);
                    if ($autoPilot->cukeConfDeletionExecute && $this->content["cukeConfDeletionResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Cuke Conf Reset Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }
                    $this->content["cukeConfAdditionResult"] = $cukeConfModel->runAutoPilotAddition($autoPilot);
                    if ($autoPilot->cukeConfAdditionExecute && $this->content["cukeConfAdditionResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Cuke Conf Creator Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    // Versioning
                    $versionModel = new \Model\Version();
                    $this->content["versioningResult"] = $versionModel->runAutoPilotVersion($autoPilot);
                    if ($autoPilot->versionExecute && $this->content["versioningResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Versioning Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    // V Host Editor
                    $VHostEditorModel = new \Model\VHostEditor();
                    $this->content["virtualHostCreatorResult"] = $VHostEditorModel->runAutoPilotVHostCreation($autoPilot);
                    if ($autoPilot->virtualHostEditorAdditionExecute && $this->content["virtualHostCreatorResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Virtual Host Creator Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                    $this->content["virtualHostDeletionResult"] = $VHostEditorModel->runAutoPilotVHostDeletion($autoPilot);
                    if ($autoPilot->virtualHostEditorDeletionExecute && $this->content["virtualHostDeletionResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Virtual Host Deletor Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                }

                else {
                    $this->content["autoPilotErrors"]="Auto Pilot couldn't load"; } }

            else {
                $this->content["autoPilotErrors"]="Auto Pilot not defined";  } }

        else {
            $this->content["autoPilotErrors"]="No Action"; }

        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);

    }

    private function loadAutoPilot($autoPilotFileName){
        $autoPilotFile = getcwd().'/'.escapeshellcmd($autoPilotFileName);
        $defaultFolderToCheck = getcwd()."/build/config/dapperstrano/autopilots";
        $defaultName = $defaultFolderToCheck.'/'.$autoPilotFileName.".php";
        $defaultAndPilotName = $defaultFolderToCheck.'/'."autopilot-".$autoPilotFileName.".php";
        if (file_exists($defaultName)) {
          require_once($defaultName); }
        else if (file_exists($defaultAndPilotName)) {
          require_once($defaultAndPilotName); }
        else if (file_exists($autoPilotFile)) {
          require_once($autoPilotFile); } 
        $autoPilot = (class_exists('\Core\AutoPilotConfigured')) ?
          new \Core\AutoPilotConfigured() : null ;
        return $autoPilot;
    }
    
}
