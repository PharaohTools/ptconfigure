<?php

Namespace Model;

class HostEditorAllLinuxMac extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

    private $hostFileData;
    private $uri;
    private $ipAddress;

    public function runAutoPilot($autoPilot){
        $auto1 = $this->runAutoPilotHostDeletion($autoPilot);
        $auto2 = $this->runAutoPilotHostAddition($autoPilot);
        return  ( $auto1==true || $auto2==true ) ? true : false ;
    }

    public function askWhetherToDoHostEntry(){
        return $this->performHostAddition();
    }

    public function askWhetherToDoHostRemoval(){
        return $this->performHostDeletion();
    }

    private function performHostAddition(){
        $hostFileEntry = $this->askForHostEntryToScreen();
        if (!$hostFileEntry) { return false; }
        $this->ipAddress = $this->askForIPEntryToScreen();
        if ($this->ipAddress=="") {$this->ipAddress="127.0.0.1";}
        $this->uri = $this->askForHostfileUri();
        $this->loadCurrentHostFile();
        $this->hostFileDataAdd($this->ipAddress, $this->uri);
        $this->checkHostFileOkay();
        $this->createHostFile();
        $this->moveHostFileAsRoot();
        return true;
    }

    private function performHostDeletion(){
        $hostFileDel = $this->askForHostDeletionToScreen();
        if (!$hostFileDel) { return false; }
        $this->ipAddress = $this->askForIPEntryToScreen();
        if ($this->ipAddress=="") {$this->ipAddress="127.0.0.1";}
        $this->uri = $this->askForHostfileUri();
        $this->loadCurrentHostFile();
        $this->hostFileDataRemove($this->ipAddress, $this->uri);
        $this->checkHostFileOkay();
        $this->createHostFile();
        $this->moveHostFileAsRoot();
        return true;
    }

    public function runAutoPilotHostAddition($autoPilot){
        $hostFileEntry =
        (isset($autoPilot["hostEditorAdditionExecute"]) && $autoPilot["hostEditorAdditionExecute"]==true)
          ? true : false;
        if (!$hostFileEntry) { return false; }
        $ipEntry = $autoPilot["hostEditorAdditionIP"];
        $uri = $autoPilot["hostEditorAdditionURI"];
        $this->loadCurrentHostFile();
        $this->hostFileDataAdd($ipEntry, $uri);
        $this->createHostFile();
        $this->moveHostFileAsRoot();
        return true;
    }

    public function runAutoPilotHostDeletion($autoPilot){
        $hostFileEntry =
          (isset($autoPilot["hostEditorDeletionExecute"]) && $autoPilot["hostEditorDeletionExecute"]==true)
          ? true : false;
        if (!$hostFileEntry) { return false; }
        $ipEntry = $autoPilot["hostEditorDeletionIP"];
        $uri = $autoPilot["hostEditorDeletionURI"];
        $this->loadCurrentHostFile();
        $this->hostFileDataRemove($ipEntry, $uri);
        $this->createHostFile();
        $this->moveHostFileAsRoot();
        return true;
    }

    private function askForHostEntryToScreen(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to add a hosts file entry?';
        return self::askYesOrNo($question);
    }

    private function askForHostDeletionToScreen(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to remove a hosts file entry?';
        return self::askYesOrNo($question);
    }

    private function askForIPEntryToScreen(){
        if (isset($this->params["host-ip"])) { return $this->params["host-ip"] ; }
        $question = 'Do you want a non-default IP? Enter for 127.0.0.1';
        return self::askForInput($question);
    }

    private function askForHostfileUri(){
        if (isset($this->params["host-name"])) { return $this->params["host-name"] ; }
        if (isset($this->params["hostname"])) { return $this->params["hostname"] ; }
        $question = 'What URI do you want to affect to the hostfile?';
        return self::askForInput($question, true);
    }

    private function loadCurrentHostFile() {
        $command = 'sudo cat /etc/hosts';
        $this->hostFileData = self::executeAndLoad($command);
    }

    private function checkHostFileOkay() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Please check host file: '.$this->hostFileData."\n\nIs this Okay? ";
        return self::askYesOrNo($question);
    }

    private function createHostFile() {
        $tmpDir = $this->baseTempDir.'/hostfile/';
        if (!file_exists($tmpDir)) { mkdir ($tmpDir, 0777, true); }
        return file_put_contents($tmpDir.'hosts', $this->hostFileData);
    }

    private function moveHostFileAsRoot(){
        $command = 'sudo mv '.$this->baseTempDir.'/hostfile/hosts /etc/hosts';
        self::executeAndOutput($command);
    }

    private function hostFileDataAdd($ipEntry, $uri){
        $hostFileLines = explode("\n", $this->hostFileData) ;
        $newHostFileData = "";
        foreach ($hostFileLines as $line) {
            $ipOccurs = substr_count($line, $ipEntry) ;
            $uriOccurs = substr_count($line, $uri) ;
            $bothOccur = ( $ipOccurs==1 && $uriOccurs==1);
            if ( $bothOccur )  {
                return; }
            else {
                $newHostFileData .= $line."\n"; } }
        $this->hostFileData .= "$ipEntry          $uri\n";
        $this->writeHostFileEntryToProjectFile();
    }

    private function hostFileDataRemove($ipEntry, $uri){
        $hostFileLines = explode("\n", $this->hostFileData) ;
        $newHostFileData = "";
        foreach ($hostFileLines as $line) {
            $ipOccurs = substr_count($line, $ipEntry) ;
            $uriOccurs = substr_count($line, $uri) ;
            $bothOccur = ( $ipOccurs==1 && $uriOccurs==1);
            if ( !$bothOccur )  {
                $newHostFileData .= $line."\n"; } }
        $this->hostFileData = $newHostFileData;
        $this->deleteHostFileEntryFromProjectFile();
    }

    private function writeHostFileEntryToProjectFile(){
        if ($this->checkIsPharoahProject()){
            $appSettingsFactory = new \AppSettings();
            $appConfig = $appSettingsFactory->getModel($this->params, "AppConfig") ;
            $appConfig::setProjectVariable("host-entries", array("$this->uri" => "$this->ipAddress") );  }
    }

    private function deleteHostFileEntryFromProjectFile(){
        if ($this->checkIsPharoahProject()) {
            $appSettingsFactory = new \AppSettings();
            $appConfig = $appSettingsFactory->getModel($this->params, "AppConfig") ;
            $allHostFileEntries = $appConfig::getProjectVariable("host-entries");
            if ($allHostFileEntries instanceof \stdClass) { $allHostFileEntries = new \ArrayObject($allHostFileEntries); }
            for ($i = 0; $i<=count($allHostFileEntries) ; $i++ ) {
                if (isset($allHostFileEntries[$i]) && array_key_exists($allHostFileEntries[$i], $this->uri)) {
                    unset($allHostFileEntries[$i]); } }
            $appConfig::setProjectVariable("host-entries", $allHostFileEntries); }
    }

    private function checkIsPharoahProject() {
        return file_exists('papyrusfile');
    }

}