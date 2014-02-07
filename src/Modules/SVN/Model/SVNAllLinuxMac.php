<?php

Namespace Model;

class SVNAllLinuxMac extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $projectDirectory;
    private $webServerUser;

    public function runAutoPilot($autoPilot){
        $this->runAutoPilotDeletor($autoPilot);
        $this->runAutoPilotCloner($autoPilot);
        return true;
    }

    public function runAutoPilotCloner($autoPilot){
        if (!$autoPilot["svnCheckoutExecute"]) {return false; }
        $this->params["svnCheckoutProjectOriginRepo"] = $autoPilot["svnCheckoutProjectOriginRepo"];
        $this->params["svnCheckoutCustomCloneFolder"] = $autoPilot["svnCheckoutCustomCloneFolder"];
        // $this->params["svnCheckoutCustomBranch"] = $autoPilot["svnCheckoutCustomBranch"];
        if (!$this->doSVNCommand() ) {return false; }
        $this->setWebServerUser($autoPilot);
        $this->changeNewProjectPermissions();
        $this->changeNewProjectFolderOwner();
        $this->changeToProjectDirectory();
        return true;
    }

    public function runAutoPilotDeletor($autoPilot){
        if (!$autoPilot["svnDeletorExecute"]) {return false; }
        $this->projectDirectory = (getcwd().DIRECTORY_SEPARATOR.$autoPilot["svnDeletorCustomFolder"]);
        $this->dropDirectory();
        return true;
    }

    public function checkoutProject($params=null){
        if ($this->askWhetherToDownload() != true) { return false; }
        if ($params==null) {
            $this->params["svnCheckoutProjectOriginRepo"] = $this->askForSVNTargetRepo(); }
        $this->doSVNCommand($params);
        if (!$this->askAlsoChangePerms() ) {return false; }
        $this->setWebServerUser();
        $this->changeNewProjectPermissions();
        $this->changeNewProjectFolderOwner();
        $this->changeToProjectDirectory();
        return true;
    }

    private function askWhetherToDownload() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Perform a clone/download of files?';
        return self::askYesOrNo($question);
    }

    private function askForSVNTargetRepo(){
        $question = 'What\'s svn repo to clone from?';
        return self::askForInput($question, true);
    }

    private function askAlsoChangePerms(){
        $question = 'Also change permissions/owner?';
        return self::askYesOrNo($question);
    }

    private function doSVNCommand(){
        $customCloneFolder =
            (isset($this->params["svnCheckoutCustomCloneFolder"]) && ($this->params["svnCheckoutCustomCloneFolder"]) != "none")
            ? $this->params["svnCheckoutCustomCloneFolder"] : null ;
        $command  = 'svn co '.escapeshellarg($this->params["svnCheckoutProjectOriginRepo"]);
        if (isset($customCloneFolder)) { $command .= ' '.escapeshellarg($customCloneFolder); }
        $nameInRepo = substr($this->params["svnCheckoutProjectOriginRepo"], strrpos($this->params["svnCheckoutProjectOriginRepo"], '/', -1) );
        $this->projectDirectory = (isset($customCloneFolder)) ? $customCloneFolder : $nameInRepo ;
        return self::executeAndLoad($command);
    }

    private function dropDirectory(){
        $command  = 'sudo rm -rf '.$this->projectDirectory;
        return self::executeAndOutput($command);
    }

    private function changeToProjectDirectory(){
        if (file_exists(getcwd().DIRECTORY_SEPARATOR.$this->projectDirectory)) {
            chdir(getcwd().DIRECTORY_SEPARATOR.$this->projectDirectory); }
        else {
            echo "Could not navigate to: ".getcwd().'/'.$this->projectDirectory."\n"; }
        echo "Now in: ".getcwd()."\n\n";
    }

    private function setWebServerUser($autoPilot = null){
        if ($autoPilot != null) { $this->webServerUser = $autoPilot["svnCheckoutWebServerUser"]; }
        else { $this->webServerUser = $this->askWebServerUser(); }
    }

    private function changeNewProjectPermissions(){
        $command  = 'sudo chmod -R 755 '.getcwd().DIRECTORY_SEPARATOR.$this->projectDirectory;
        self::executeAndOutput($command, "Changing Folder Permissions...");
    }

    private function changeNewProjectFolderOwner(){
        $command  = 'sudo chown -R '.$this->webServerUser.' '.$this->projectDirectory;
        self::executeAndOutput($command, "Changing Folder Owner...");
    }

    private function askWebServerUser(){
        $question = 'What user is Apache Web Server running as?';
        if ($this->detectDebianApacheVHostFolderExistence()) { $question .= ' Guessed ubuntu:www-data - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? "www-data" : $input ;  }
        if ($this->detectRHVHostFolderExistence()) { $question .= ' Guessed Centos/RH:apache - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? "apache" : $input ;  }
        return self::askForInput($question, true);
    }

    private function detectDebianApacheVHostFolderExistence(){
        return file_exists("/etc/apache2/sites-available");
    }

    private function detectRHVHostFolderExistence(){
        return file_exists("/etc/httpd/vhosts.d");
    }

}