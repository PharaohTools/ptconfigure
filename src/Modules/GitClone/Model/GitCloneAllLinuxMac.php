<?php

Namespace Model;

class GitCloneAllLinuxMac extends Base {

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

    public function checkoutProject(){
        if ($this->askWhetherToDownload() != true) { return false; }
        $this->askForGitCloneTargetRepo();
        $passed = false ;
        for ($tried = 0; $tried < 3; $tried++) {
            $passed = $this->doGitCloneCommand();
            if ($passed == true) { break ; }
            sleep(2) ; }
        if ($passed == false) { return false; }
        if ($this->askAlsoChangePerms() == false ) { return true; }
        $this->setWebServerUser();
        $this->changeNewProjectPermissions();
        $this->changeNewProjectFolderOwner();
//        $this->changeToProjectDirectory();
        return true;
    }

    protected function askWhetherToDownload() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Perform a clone/download of files?';
        return self::askYesOrNo($question);
    }

    protected function askForGitCloneTargetRepo() {
        if (isset($this->params["repository-url"])) { return $this->params["repository-url"] ; }
        $question = 'What\'s git repo to clone from?';
        $this->params["repository-url"] = self::askForInput($question, true);
    }

    protected function askAlsoChangePerms() {
        if (isset($this->params["change-owner-permissions"]) && $this->params["change-owner-permissions"]!==true) { return false ; }
        $question = 'Also change permissions/owner?';
        return self::askYesOrNo($question);
    }
// @todo scrap this
//    protected function doGitCloneCommandWithErrorCheck($params){
//        $data = $this->doGitCloneCommand($params);
//        print $data;
//        if ( substr($data, 0, 5) == "error" ) { return false; }
//        return true;
//    }

    // @todo there needs to be a dependency check for git-safe-key module to be installed by ptconfigure
    protected function getGitCommand() {
        if (isset($this->params["private-key"]) && strlen($this->params["private-key"])>0) {
            return 'git-key-safe -i '.$this->params["private-key"] ; }
        else {
            return 'git' ; }
    }

    protected function doGitCloneCommand() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting Git Clone...", $this->getModuleName());
        $projectOriginRepo = $this->params["repository-url"] ;
        $customCloneFolder = (isset($this->params["custom-clone-dir"])) ? $this->params["custom-clone-dir"] : null ;
        $customBranch = (isset($this->params["custom-branch"])) ? $this->params["custom-branch"] : null ;
        // @todo the git --single-branch option gave errors on centos 6.2, so instead of cloning a single branch I
        // changed it to clone whole repo then switch to specified. works on ubuntu and centos
        // $branchParam = ($customBranch!=null) ? $customBranch.' --single-branch ' : "" ;
        $branchParam = ($customBranch != null) ? '--branch '.escapeshellarg($customBranch).' ' : "" ;
        $command  = $this->getGitCommand().' clone '.$branchParam.escapeshellarg($projectOriginRepo);
        $nameInRepo = substr($projectOriginRepo, strrpos($projectOriginRepo, '/', -1) +1 );
        if (isset($customCloneFolder)) { $command .= ' '.escapeshellarg($customCloneFolder); }
        else { $command .= ' '.$nameInRepo; }
        $this->projectDirectory = (isset($customCloneFolder)) ? $customCloneFolder : $nameInRepo ;
//        $command .= " ".$this->projectDirectory ;
//        var_dump($command);
//        echo $command;
        $cwd = getcwd() ;
        $fullpath = $this->projectDirectory ;
        $logging->log("Cloning to {$fullpath}", $this->getModuleName());

//        ob_start() ;
//        var_dump($this->params) ;
//        $out = ob_get_clean() ;
//        $logging->log("pars {$out}", $this->getModuleName());

        $try_res = false ;
        for ($i=1; $i<=3; $i++) {
            $logging->log("Cloning $projectOriginRepo, Attempt {$i}", $this->getModuleName());
            $logging->log("Command: {$command}", $this->getModuleName());
            $try_res = self::executeAndGetReturnCode($command, false, true);
//        ob_start() ;
//        var_dump("tr:", $try_res) ;
//        $out = ob_get_clean() ;
//        $logging->log("tr: {$out}", $this->getModuleName());
            if ($try_res["rc"] == 0) { break ; } }
        $msg = (isset($try_res["rc"]) && $try_res["rc"] == 0) ? "Successful" : "Failed";
        if (isset($try_res["rc"]) && $try_res["rc"] == 0) {
            $logging->log("Clone $msg", $this->getModuleName());
            return true ; }
        $logging->log("Clone $msg", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
        return false ;
    }

    protected function dropDirectory(){
        $command  = 'sudo rm -rf '.$this->projectDirectory;
        return self::executeAndOutput($command);
    }

    protected function changeToProjectDirectory(){
        if (file_exists(getcwd().DIRECTORY_SEPARATOR.$this->projectDirectory)) {
            chdir(getcwd().DIRECTORY_SEPARATOR.$this->projectDirectory); }
         else {
             echo "Could not navigate to: ".getcwd().'/'.$this->projectDirectory."\n"; }
        echo "Now in: ".getcwd()."\n\n";
    }

    // @todo make this a param or remove to another module as its not technically a git command
    protected function setWebServerUser(){
        $this->webServerUser = $this->askWebServerUser();
    }

    // @todo make this a param or remove to another module as its not technically a git command
    protected function changeNewProjectPermissions(){
        $command  = 'sudo chmod -R 755 '.getcwd().DIRECTORY_SEPARATOR.$this->projectDirectory;
        self::executeAndOutput($command, "Changing Folder Permissions...");
    }

    // @todo make this a param or remove to another module as its not technically a git command
    protected function changeNewProjectFolderOwner(){
        $command  = 'sudo chown -R '.$this->webServerUser.' '.$this->projectDirectory;
        self::executeAndOutput($command, "Changing Folder Owner...");
    }

    protected function askWebServerUser(){
        $question = 'What user is Apache Web Server running as?';
        if ($this->detectDebianApacheVHostFolderExistence()) {
            if (isset($this->params["guess"])) { return "www-data" ; }
            $question .= ' Guessed ubuntu:www-data - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? "www-data" : $input ;  }
        if ($this->detectRHVHostFolderExistence()) {
            if (isset($this->params["guess"])) { return "apache" ; }
            $question .= ' Guessed Centos/RH:apache - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? "apache" : $input ;  }
        return self::askForInput($question, true);
    }

    protected function detectDebianApacheVHostFolderExistence(){
        return file_exists("/etc/apache2/sites-available");
    }

    protected function detectRHVHostFolderExistence(){
        return file_exists("/etc/httpd/vhosts.d");
    }

}