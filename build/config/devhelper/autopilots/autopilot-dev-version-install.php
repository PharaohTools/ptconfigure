<?php

Namespace Core ;

class AutoPilot {

    // SSH Invoke Variables
    public $sshInvokeSSHDataExecute = false;
    public $sshInvokeSSHDataData = null;
    public $sshInvokeSSHScriptExecute = false;
    public $sshInvokeSSHScriptFile = null;
    public $sshInvokeServers = array( array("target"=>"127.0.0.1", "user"=>"dave", "pword"=>"milk") );

    // Git Checkout Variables
    public $gitCheckoutExecute = true;
    public $gitCheckoutProjectOriginRepo = 'http://github.com/phpengine/rock-paper-scissors-drupal';
    public $gitCheckoutCustomCloneFolder = null; // set by constructor called function

    // Git Project Deletor
    public $gitDeletorExecute = false;
    public $gitDeletorCustomFolder = null;

    // Project Init Variables
    public $projectInitializeExecute = false;

    // Project Build Variables
    public $projectBuildInstallExecute = false;
    public $projectJenkinsOriginalJobFolderName = false;
    public $projectJenkinsNewJobFolderName = false;
    public $projectJenkinsFSFolder = false;

    // Host File Editor Addition Variables
    public $hostEditorAdditionExecute = false;
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

    // Version
    public $versionExecute = true;
    public $versionAppRootDirectory = null; // set below
    public $versionArrayPointToRollback = "0"; // 0 latest, 1 rollback 1, 2, etc as available

    public function __construct() {
        $this->calculateVHostDocRoot();
        $this->setVersionTimestamp();
    }

    private function calculateVHostDocRoot() {
        $this->virtualHostEditorAdditionDocRoot = getcwd().'/current/src';
        $this->versionAppRootDirectory = getcwd();
    }

    private function setVersionTimestamp() {
        $this->gitCheckoutCustomCloneFolder = time();
    }

}
