<?php

Namespace Model;

class GitCheckout extends Base {

    private $projectDirectory;
    private $webServerUser;

    public function runAutoPilotCloner($autoPilot){
        if (!$autoPilot->gitCheckoutExecute) {return false; }
        $params[0] = $autoPilot->gitCheckoutProjectOriginRepo;
        $params[1] = $autoPilot->gitCheckoutCustomCloneFolder;
        $params[2] = $autoPilot->gitCheckoutCustomBranch;
        if (!$this->doGitCommandWithErrorCheck($params) ) {return false; }
        $this->setWebServerUser($autoPilot);
        $this->changeNewProjectPermissions();
        $this->changeNewProjectFolderOwner();
        $this->changeToProjectDirectory();
        return true;
    }

    public function runAutoPilotDeletor($autoPilot){
        if (!$autoPilot->gitDeletorExecute) {return false; }
        $this->projectDirectory = (getcwd().'/'.$autoPilot->gitDeletorCustomFolder);
        $this->dropDirectory();
        return true;
    }

    public function checkoutProject($params=null){
        if ($params==null) {
            $params = array();
            $params[0] = $this->askForGitTargetRepo(); }
        $this->doGitCommand($params);
        if (!$this->askAlsoChangePerms() ) {return false; }
        $this->setWebServerUser();
        $this->changeNewProjectPermissions();
        $this->changeNewProjectFolderOwner();
        $this->changeToProjectDirectory();
        return true;
    }

    private function askForGitTargetRepo(){
        $question = 'What\'s git repo to clone from?';
        return self::askForInput($question, true);
    }

    private function askAlsoChangePerms(){
        $question = 'Also change permissions/owner?';
        return self::askYesOrNo($question);
    }

    private function doGitCommandWithErrorCheck($params){
        $data = $this->doGitCommand($params);
        print $data;
        if (substr($data, 0, 5)=="error") {
            return false; }
        return true;
    }

    private function doGitCommand($params){
        $projectOriginRepo = $params[0];
        $customCloneFolder = (isset($params[1]) && ($params[1]) != "none") ? $params[1] : null ;
        $customBranch = (isset($params[2]) && ($params[2]) != "none") ? $params[2] : null ;
        // @todo the git --single-branch option gave errors on centos 6.2, so instead of cloning a single branch I
        // changed it to clone whole repo then switch to specified. works on ubuntu
        $branchParam = ($customBranch!=null) ? $customBranch.' --single-branch ' : "" ;
        // $branchParam = ($customBranch!=null) ? '-b '.escapeshellarg($customBranch).' ' : "" ;
        $command  = 'git clone '.$branchParam.escapeshellarg($projectOriginRepo);
        if (isset($customCloneFolder)) {
            $command .= ' '.escapeshellarg($customCloneFolder); }
        $nameInRepo = substr($projectOriginRepo, strrpos($projectOriginRepo, '/', -1) );
        $this->projectDirectory = (isset($customCloneFolder)) ? $customCloneFolder : $nameInRepo ;
        return self::executeAndLoad($command);
    }

    private function dropDirectory(){
        $command  = 'sudo rm -rf '.$this->projectDirectory;
        return self::executeAndOutput($command);
    }

    private function changeToProjectDirectory(){
        if (file_exists(getcwd().'/'.$this->projectDirectory)) {
            chdir(getcwd().'/'.$this->projectDirectory); }
         else {
             echo "Could not navigate to: ".getcwd().'/'.$this->projectDirectory."\n"; }
        echo "Now in: ".getcwd()."\n\n";
    }

    private function setWebServerUser($autoPilot = null){
        if ($autoPilot != null) { $this->webServerUser = $autoPilot->gitWebServerUser; }
        else { $this->webServerUser = $this->askWebServerUser(); }
    }

    private function changeNewProjectPermissions(){
        $command  = 'sudo chmod -R 755 '.getcwd().'/'.$this->projectDirectory;
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