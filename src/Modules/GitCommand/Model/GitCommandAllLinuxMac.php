<?php

Namespace Model;

class GitCommandAllLinuxMac extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $actionsToMethods =
        array(
            "checkout-branch" => "checkoutBranch",
            "create-checkout-branch" => "createCheckoutBranch",
            "delete-branch" => "deleteBranch",
            "ensure-branch" => "ensureBranch",
            "add",
            "commit",
            "push",
            "pull"
        ) ;

    private $projectDirectory;

    protected function askWhetherToDoCommand() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Perform a git command?';
        return self::askYesOrNo($question);
    }

    public function createCheckoutBranch(){
        if ($this->askWhetherToDoCommand() != true) { return false; }
        $this->askForBranch();
        $out = $this->doCreateCheckoutBranch(true);
        $this->changeToProjectDirectory();
        return $out;
    }

    public function checkoutBranch(){
        if ($this->askWhetherToDoCommand() != true) { return false; }
        $this->askForBranch();
        $out = $this->doCreateCheckoutBranch();
        $this->changeToProjectDirectory();
        return $out;
    }

    public function deleteBranch(){
        if ($this->askWhetherToDoCommand() != true) { return false; }
        $this->askForBranch();
        $out = $this->doDeleteBranch();
        $this->changeToProjectDirectory();
        return $out;
    }

    public function ensureBranch(){
        if ($this->askWhetherToDoCommand() != true) { return false; }
        $this->askForBranch();
        $out = $this->doEnsureBranch();
        $this->changeToProjectDirectory();
        return $out;
    }

    protected function askForGitRepo() {
        if (isset($this->params["repository-url"])) { return $this->params["repository-url"] ; }
        $question = 'What\'s your git repository?';
        $this->params["repository-url"] = self::askForInput($question, true);
    }

    protected function askForBranch() {
        if (isset($this->params["branch"])) { return $this->params["branch"] ; }
        $question = 'What branch?';
        $this->params["branch"] = self::askForInput($question, true);
    }

    // @todo there needs to be a dependency check for git-safe-key module to be installed by cleopatra
    protected function getGitCommand() {
        if (isset($this->params["private-key"]) && strlen($this->params["private-key"])>0) {
            return 'git-key-safe -i '.$this->params["private-key"] ; }
        else {
            return 'git' ; }
    }

    protected function doCreateCheckoutBranch($create = null) {
        $flag = ($create) ? "-b " : "" ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting to create branch {$this->params["branch"]}", $this->getModuleName());
        $command = $this->getGitCommand().' checkout '.$flag.$this->params["branch"] ;
        return self::executeAndGetReturnCode($command);
    }

    protected function doDeleteBranch() {
        $branches = self::executeAndLoad($this->getGitCommand().' branch');
        $exists = (strpos($branches, "{$this->params["branch"]}\n") !== false) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (!$exists) {
            $logging->log("Branch {$this->params["branch"]} does not exist, failing...", $this->getModuleName());
            \Core\BootStrap::setExitCode(1);
            return 1 ; }
        $logging->log("Branch {$this->params["branch"]} exists, deleting...", $this->getModuleName());
        $command = $this->getGitCommand().' branch -d '.$this->params["branch"] ;
        echo $command . "\n" ;
        return self::executeAndGetReturnCode($command);
    }

    protected function doEnsureBranch() {
        $branches = self::executeAndLoad($this->getGitCommand().' branch');
        $exists = (strpos($branches, "{$this->params["branch"]}\n") !== false) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($exists) {
            $logging->log("Branch {$this->params["branch"]} already exists, continuing...", $this->getModuleName());
            return true ; }
        $logging->log("Branch {$this->params["branch"]} does not exist, creating...", $this->getModuleName());
        $command = $this->getGitCommand().' checkout -b '.$this->params["branch"] ;
        return self::executeAndGetReturnCode($command);
    }

    protected function changeToProjectDirectory(){
        if (file_exists(getcwd().DIRECTORY_SEPARATOR.$this->projectDirectory)) {
            chdir(getcwd().DIRECTORY_SEPARATOR.$this->projectDirectory); }
        else {
            echo "Could not navigate to: ".getcwd().DIRECTORY_SEPARATOR.$this->projectDirectory."\n"; }
        echo "Now in: ".getcwd()."\n\n";
    }

}