<?php

Namespace Core ;

class AutoPilot {

    // SSH Invoke Variables
    public $sshInvokeSSHDataExecute = null;
    public $sshInvokeSSHScriptExecute = null;
    public $sshInvokeServers = null;
    public $sshInvokeDeploymentExecute = null;

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
    public $projectJenkinsOriginalJobFolderName = false;
    public $projectJenkinsNewJobFolderName = false;
    public $projectJenkinsFSFolder = false;

    // Host File Editor Addition Variables
    public $hostEditorAdditionExecute = true;
    public $hostEditorAdditionIP = '127.0.0.1';
    public $hostEditorAdditionURI = 'rps-drupal.testsite.tld';

    // Host File Editor Deletion Variables
    public $hostEditorDeletionExecute = false;
    public $hostEditorDeletionIP = null;
    public $hostEditorDeletionURI = null;

    // Virtual Host Editor Addition Variables
    public $virtualHostEditorAdditionExecute = true;
    public $virtualHostEditorAdditionDocRoot;
    public $virtualHostEditorAdditionURL = 'rps-drupal.testsite.tld';
    public $virtualHostEditorAdditionFileSuffix = '';
    public $virtualHostEditorAdditionIp = '*:80';
    public $virtualHostEditorAdditionDirectory = '/etc/apache2/sites-available';
    public $virtualHostEditorAdditionVHostEnable = true;
    public $virtualHostEditorAdditionSymLinkDirectory = '/etc/apache2/sites-enabled';
    public $virtualHostEditorAdditionApacheCommand = "apache2";

    // Virtual Host Editor Deletion Variables
    public $virtualHostEditorDeletionExecute = false;
    public $virtualHostEditorDeletionIP = null;
    public $virtualHostEditorDeletionURI = null;
    public $virtualHostEditorDeletionVHostDisable = false;
    public $virtualHostEditorDeletionSymLinkDirectory = null;
    public $virtualHostEditorDeletionApacheCommand = "apache2";

    // DB Configuration Reset - App Settings
    public $dbResetExecute = true;
    public $dbResetPlatform = 'drupal7' ;

    // DB Configuration Setup - App Settings
    public $dbConfigureExecute = true;
    public $dbConfigurePlatform = 'drupal7';
    public $dbConfigureDBHost = '127.0.0.1';
    public $dbConfigureDBUser = 'rpstestuser';
    public $dbConfigureDBPass = 'rpstestpass';
    public $dbConfigureDBName = 'rpstestdb';

    // DB Install - Install DB and User
    public $dbInstallExecute = true;
    public $dbInstallDBHost = '127.0.0.1';
    public $dbInstallDBUser = 'rpstestuser';
    public $dbInstallDBPass = 'rpstestpass';
    public $dbInstallDBName = 'rpstestdb';
    public $dbInstallDBRootUser = 'gcTestAdmin';
    public $dbInstallDBRootPass = 'gcTest1234';

    // DB Drop - Drop DB
    public $dbDropExecute = false;
    public $dbDropDBName = null;
    public $dbDropDBHost = null;
    public $dbDropDBRootUser = null;
    public $dbDropDBRootPass = null;

    // Cuke Conf Addition Variables
    public $cukeConfAdditionExecute = true;
    public $cukeConfAdditionURI = 'http://rps-drupal.testsite.tld';

    // Cuke Conf Deletion Variables
    public $cukeConfDeletionExecute = false;

    public function __construct() {
	    $this->calculateVHostDocRoot();
    }

    private function calculateVHostDocRoot() {
	    $this->virtualHostEditorAdditionDocRoot = getcwd().'/'.$this->gitCheckoutCustomCloneFolder;
    }

}










