<?php

Namespace Model;

class Version extends Base {

    private $appRootDirectory ;
    private $appVersion ;

    public function askWhetherToVersionSpecific(){
        return $this->performSymLinkVersion();
    }

    public function askWhetherToVersionLatest(){
        return $this->performSymLinkVersion("0");
    }

    public function askWhetherToVersionRollback(){
        return $this->performSymLinkVersion("1");
    }

    private function performSymLinkVersion($arrayPointToRollback = null) {
        if ( !$this->askForSymLinkChange() ) { return false; }
        $this->appRootDirectory = $this->selectAppRoot();
        $this->appVersion = $this->selectAppVersion($arrayPointToRollback);
        $this->symlinkRemover();
        $this->symlinkCreator();
        return "Seems Fine...";
    }

    public function runAutoPilotVersion($autoPilot){
        if ( !$autoPilot->versionExecute ) { return false; }
        $this->appRootDirectory = $autoPilot->versionAppRootDirectory;
        $this->appVersion = $this->selectAppVersion($autoPilot->versionArrayPointToRollback);
        $this->symlinkRemover();
        $this->symlinkCreator();
        return true;
    }

    private function askForSymLinkChange(){
        $question = 'Do you want to change the version that *current* points to?';
        return self::askYesOrNo($question);
    }

    private function selectAppVersion($version){
        $otherResults = scandir($this->appRootDirectory);
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
        if (isset($version) ) { return $availableVersions[$version] ; }
        else {
            $validChoice = false;
            while ($validChoice == false) {
                $input = self::askForInput($question) ;
                if ( array_key_exists($input, $availableVersions) ){
                    $validChoice = true;} }
            return $availableVersions[$input] ; }
    }

    private function selectAppRoot(){
        $question = 'What is the Application Root Directory? (The one with versions in) Enter none for '.getcwd();
        $input = self::askForInput($question) ;
        return ($input=="") ? getcwd() : $input ;
    }

    private function symlinkRemover() {
        $command  = 'rm -f '.$this->appRootDirectory.'/current';
        echo "Removed Version Symlink\n";
        self::executeAndOutput($command);
    }

    private function symlinkCreator() {
        $command  = 'ln -s '.$this->appRootDirectory.'/'.$this->appVersion.' '.$this->appRootDirectory.'/current';
        echo "Created Version Symlink\n";
        self::executeAndOutput($command);
    }


}