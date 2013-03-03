<?php

Namespace Model;

class GitCheckout extends Base {

    private $projectDirectory;

    public function runAutoPilotCloner($autoPilot){
        if (!$autoPilot->gitCheckoutExecute) {return false; }
        $params[0] = $autoPilot->gitCheckoutProjectOriginRepo;
        $params[1] = $autoPilot->gitCheckoutCustomCloneFolder;
        if (!$this->doGitCommandWithErrorCheck($params) ) {return false; }
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
        $this->changeToProjectDirectory();
        return true;
    }

    private function askForGitTargetRepo(){
        $question = 'What\'s git repo to clone from?';
        return self::askForInput($question, true);
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
        $customCloneFolder = (isset($params[1])) ? $params[1] : null ;
        $command  = 'git clone '.escapeshellarg($projectOriginRepo);
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

}