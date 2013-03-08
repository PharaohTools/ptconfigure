<?php

Namespace Core ;

class AutoPilot {

    // Git Checkout Variables
    public $gitCheckoutExecute = false;
    public $gitCheckoutProjectOriginRepo = null;
    public $gitCheckoutCustomCloneFolder = null;

    // Git Project Deletor
    public $gitDeletorExecute = false;
    public $gitDeletorCustomFolder = null;

    // Project Init Variables
    public $projectInitializeExecute = true;

    // Project Build Variables
    public $projectBuildInstallExecute = false;
    public $projectJenkinsOriginalJobFolderName = null;
    public $projectJenkinsNewJobFolderName = null;
    public $projectJenkinsFSFolder = null;

    // Host File Editor Addition Variables
    public $hostEditorAdditionExecute = false;
    public $hostEditorAdditionIP = null;
    public $hostEditorAdditionURI = null;

    // Host File Editor Deletion Variables
    public $hostEditorDeletionExecute = false;
    public $hostEditorDeletionIP = null;
    public $hostEditorDeletionURI = null;

    // Virtual Host Editor Addition Variables
    public $virtualHostEditorAdditionExecute = false;
    public $virtualHostEditorAdditionDocRoot = null;
    public $virtualHostEditorAdditionURL = null;
    public $virtualHostEditorAdditionFileSuffix = '';
    public $virtualHostEditorAdditionIp = null;
    public $virtualHostEditorAdditionDirectory = null;
    public $virtualHostEditorAdditionVHostEnable = false;
    public $virtualHostEditorAdditionSymLinkDirectory = null;
    public $virtualHostEditorAdditionApacheCommand = null;

    // Virtual Host Editor Deletion Variables
    public $virtualHostEditorDeletionExecute = false;
    public $virtualHostEditorDeletionIP = null;
    public $virtualHostEditorDeletionURI = null;
    public $virtualHostEditorDeletionVHostDisable = false;
    public $virtualHostEditorDeletionSymLinkDirectory = null;
    public $virtualHostEditorDeletionApacheCommand = null;

    // DB Configuration Reset - App Settings
    public $dbResetExecute = true;
    public $dbResetPlatform = null;

    // DB Configuration Setup - App Settings
    public $dbConfigureExecute = false;
    public $dbConfigurePlatform = null;
    public $dbConfigureDBHost = null;
    public $dbConfigureDBUser = null;
    public $dbConfigureDBPass = null;
    public $dbConfigureDBName = null;

    // DB Install - Install DB and User
    public $dbInstallExecute = false;
    public $dbInstallDBHost = null;
    public $dbInstallDBUser = null;
    public $dbInstallDBPass = null;
    public $dbInstallDBName = null;
    public $dbInstallDBRootUser = null;
    public $dbInstallDBRootPass = null;

    // DB Drop - Drop DB
    public $dbDropExecute = false;
    public $dbDropDBName = null;
    public $dbDropDBHost = null;
    public $dbDropDBRootUser = null;
    public $dbDropDBRootPass = null;

    // Cuke Conf Addition Variables
    public $cukeConfAdditionExecute = false;
    public $cukeConfAdditionURI = null;

    // Cuke Conf Deletion Variables
    public $cukeConfDeletionExecute = true;

    public function __construct() {
	    $this->calculateVHostDocRoot();
    }

    private function calculateVHostDocRoot() {
	    $this->virtualHostEditorAdditionDocRoot = getcwd().'/'.$this->gitCheckoutCustomCloneFolder;
    }

}
