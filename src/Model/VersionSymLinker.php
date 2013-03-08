<?php

Namespace Model;

class VersionSymLinker extends Base {

    private $appDirectory ;
    private $appVersion ;

    public function askWhetherRePointSymlinksToFolder(){
        return $this->performDBInstallation();
    }

    public function askWhetherToDropDB(){
        return $this->performDBDrop();
    }

    private function performDBInstallation(){
        return $this->performDBInstallationWithNoConfig();
    }

    private function performDBInstallationWithNoConfig() {
        if ( !$this->askForSymLinkChange() ) { return false; }
        $this->appDirectory = $this->selectAppVersion();

        if ( !$this->verifyChangeSymlinks() ) { return false; }
        $this->symlinkRemover();
        $this->symlinkCreator();

        return "Seems Fine...";
    }

    private function performDBDrop() {
        if ( !$this->askForDBDrop() ) { return false; }
        if (!$this->useRootToDropDb() ) { return "You declined using root"; }
        $this->dbRootUser = $this->askForRootDBUser();
        $this->dbRootPass = $this->askForRootDBPass();
        $this->dbName = $this->askForDBName();
        $this->dropDB();
        return "Seems Fine...";
    }

//    public function runAutoPilotDBInstallation($autoPilot){
//        if ( !$autoPilot->dbInstallExecute ) { return false; }
//        $this->dbHost = $autoPilot->dbInstallDBHost;
//        $this->dbUser = $autoPilot->dbInstallDBUser;
//        $this->dbPass = $autoPilot->dbInstallDBPass;
//        $this->dbName = $autoPilot->dbInstallDBName;
//        $this->dbRootUser = $autoPilot->dbInstallDBRootUser;
//        $this->dbRootPass = $autoPilot->dbInstallDBRootPass;
//        $this->databaseAndUserCreator();
//        $this->sqlInstaller();
//        return true;
//    }
//
//    public function runAutoPilotDBRemoval($autoPilot){
//        if ( !$autoPilot->dbDropExecute ) { return false; }
//        $this->dbHost = $autoPilot->dbDropDBHost;
//        $this->dbName = $autoPilot->dbDropDBName;
//        $this->dbRootUser = $autoPilot->dbDropDBRootUser;
//        $this->dbRootPass = $autoPilot->dbDropDBRootPass;
//        $this->dropDB();
//        return true;
//    }

    private function askForSymLinkChange(){
        $question = 'Do you want to change the version that *current* points to?';
        return self::askYesOrNo($question);
    }

    private function selectAppVersion(){
        $otherResults = scandir($this->appDirectory);
        arsort($otherResults) ;
        $question = "Please Choose Version to make current (Showing newest first, Enter none for newest):\n";
        $i1 = 0;
        $availableVersions = array();
        if (count($otherResults)>0) {
            $question .= "--- All Versions: ---\n";
            foreach ($otherResults as $result) {
                if ($result === '.' or $result === '..') continue;
                $question .= "($i1) $result\n";
                $i1++;
                $availableVersions[] = $result;} }
        $validChoice = false;
            $input = self::askForInput($question) ;
            if ( array_key_exists($input, $availableVersions) ){
                $validChoice = true;}
        return array($availableVersions[$input]) ;
    }

    private function verifyChangeSymlinks(){
        $question = 'Ready to Change Current Version. Sure you want to continue?';
        return self::askYesOrNo($question);
    }

    private function symlinkCreator() {
        $command  = 'rm -f '.$this->appDirectory.'/current';
        self::executeAndOutput($command);
    }

    private function symlinkRemover() {
        $command  = 'ln -s '.$this->appDirectory.'/'.$this->appVersion.' '.$this->appDirectory.'/current';
        self::executeAndOutput($command);
    }


}