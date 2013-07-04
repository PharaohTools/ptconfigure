<?php

Namespace Core ;

class AutoPilot {

    // Project Container Init Variables
    public $projectContainerInitExecute = false; // true or false
    public $projectContainerDirectory = null;

    // SSH Invoke Variables
    public $sshInvokeSSHDataExecute = false; // true or false
    public $sshInvokeSSHDataData = null;
    public $sshInvokeSSHScriptExecute = false;
    public $sshInvokeSSHScriptFile = null;
    public $sshInvokeServers = array(); // array( array("target"=>"127.0.0.1", "user"=>"dave", "pword"=>"milk") );

    // Git Checkout Variables
    public $gitCheckoutExecute = false; // true or false
    public $gitCheckoutProjectOriginRepo = null;
    public $gitCheckoutCustomCloneFolder = null;
    public $gitCheckoutCustomBranch = null;
    public $gitWebServerUser = null; // apache / www-data

    // Git Project Deletor
    public $gitDeletorExecute = false; // true or false
    public $gitDeletorCustomFolder = null;

    // Project Init Variables
    public $projectInitializeExecute = false; // true or false

    // Project Build Variables
    public $projectBuildInstallExecute = false; // true or false
    public $projectJenkinsOriginalJobFolderName = null;
    public $projectJenkinsNewJobFolderName = null;
    public $projectJenkinsFSFolder = null;

    // Host File Editor Addition Variables
    public $hostEditorAdditionExecute = false; // true or false
    public $hostEditorAdditionIP = null;
    public $hostEditorAdditionURI = null;

    // Host File Editor Deletion Variables
    public $hostEditorDeletionExecute = false; // true or false
    public $hostEditorDeletionIP = null;
    public $hostEditorDeletionURI = null;

    // Virtual Host Editor Addition Variables
    public $virtualHostEditorAdditionExecute = false; // true or false
    public $virtualHostEditorAdditionDocRoot = null;
    public $virtualHostEditorAdditionURL = null;
    public $virtualHostEditorAdditionFileSuffix = null;
    public $virtualHostEditorAdditionIp = null;
    public $virtualHostEditorAdditionDirectory = null;
    public $virtualHostEditorAdditionVHostEnable = false;
    public $virtualHostEditorAdditionSymLinkDirectory = null;
    public $virtualHostEditorAdditionApacheCommand = null;
    public $virtualHostEditorAdditionTemplateData = null; // will use default template if null

    // Virtual Host Editor Deletion Variables
    public $virtualHostEditorDeletionExecute = false; // true or false
    public $virtualHostEditorDeletionIP = null;
    public $virtualHostEditorDeletionURI = null;
    public $virtualHostEditorDeletionVHostDisable = false;
    public $virtualHostEditorDeletionSymLinkDirectory = null;
    public $virtualHostEditorDeletionApacheCommand = null; // apache2 or httpd

    // DB Configuration Reset - App Settings
    public $dbResetExecute = false; // true or false
    public $dbResetPlatform = 'php' ; // php gcfw gcfw2 drupal drupal7 d7

    // DB Configuration Setup - App Settings
    public $dbConfigureExecute = false; // true or false
    public $dbConfigurePlatform = 'php'; // php gcfw gcfw2 drupal drupal7 d7
    public $dbConfigureDBHost = null;
    public $dbConfigureDBUser = null;
    public $dbConfigureDBPass = null;
    public $dbConfigureDBName = null;

    // DB Drop - Drop DB
    public $dbDropExecute = false; // true or false
    public $dbDropDBName = null;
    public $dbDropDBHost = null;
    public $dbDropDBRootUser = null;
    public $dbDropDBRootPass = null;
    public $dbDropUserExecute = false; // true or false
    public $dbDropDBUser = null;

    // DB Install - Install DB and User
    public $dbInstallExecute = false; // true or false
    public $dbInstallDBHost = null;
    public $dbInstallDBUser = null;
    public $dbInstallDBPass = null;
    public $dbInstallDBName = null;
    public $dbInstallDBRootUser = null;
    public $dbInstallDBRootPass = null;

    // Cuke Conf Deletion Variables
    public $cukeConfDeletionExecute = false; // true or false

    // Cuke Conf Addition Variables
    public $cukeConfAdditionExecute = false; // true or false
    public $cukeConfAdditionURI = null;

    // Version
    public $versionExecute = false; // true or false
    public $versionAppRootDirectory = null;
    public $versionArrayPointToRollback = null;

    public function __construct() {
        $this->calculateVHostDocRoot();
    }

    private function calculateVHostDocRoot() {
        $this->virtualHostEditorAdditionDocRoot = getcwd().'/'.$this->gitCheckoutCustomCloneFolder;
    }

}
