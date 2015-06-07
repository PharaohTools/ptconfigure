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
    public $modelGroup = array("Default") ;

    private $hostFileData;
    private $uri;
    private $ipAddress;

    public function askWhetherToDoHostEntry(){
        return $this->performHostAddition();
    }

    public function askWhetherToDoHostRemoval(){
        return $this->performHostDeletion();
    }

    private function performHostAddition(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $hostFileEntry = $this->askForHostEntryToScreen();
        if (!$hostFileEntry) {
            $logging->log("Host file change refused", $this->getModuleName()) ;
            return false; }
        $this->ipAddress = $this->askForIPEntryToScreen();
        if ($this->ipAddress=="") {
            $logging->log("Using default Host IP", $this->getModuleName()) ;
            $this->ipAddress="127.0.0.1"; }
        $this->uri = $this->askForHostfileUri();
        if ($this->loadCurrentHostFile()==false) {
            $logging->log("Unable to load current Host file", $this->getModuleName()) ;
            return false; };
        $this->hostFileDataAdd($this->ipAddress, $this->uri);
        $this->checkHostFileOkay();
        $this->createHostFile();
        $this->moveHostFileAsRoot();
        return true;
    }

    private function performHostDeletion(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $hostFileDel = $this->askForHostDeletionToScreen();
        if (!$hostFileDel) { return false; }
        $this->ipAddress = $this->askForIPEntryToScreen();
        if ($this->ipAddress=="") {$this->ipAddress="127.0.0.1";}
        $this->uri = $this->askForHostfileUri();
        if ($this->loadCurrentHostFile()==false) {
            $logging->log("Unable to load current Host file", $this->getModuleName()) ;
            return false; };
        $this->hostFileDataRemove($this->ipAddress, $this->uri);
        $this->checkHostFileOkay();
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
        if (isset($this->params["guess"])) { return "127.0.0.1" ; }
        $question = 'Do you want a non-default IP? Enter for 127.0.0.1';
        return self::askForInput($question);
    }

    private function askForHostfileUri(){
        if (isset($this->params["host-name"])) { return $this->params["host-name"] ; }
        if (isset($this->params["hostname"])) { return $this->params["hostname"] ; }
        $question = 'What URI do you want to affect to the hostfile?';
        return self::askForInput($question, true);
    }
    private function checkHostFileOkay() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Please check host file: '.$this->hostFileData."\n\nIs this Okay? ";
        return self::askYesOrNo($question);
    }

    private function loadCurrentHostFile() {
        $command = 'sudo cat /etc/hosts';
        $this->hostFileData = self::executeAndLoad($command);
        return (strlen($this->hostFileData)>0) ? true : false ;
    }

    private function createHostFile() {
        $tmpDir = $this->baseTempDir.DS.'hostfile'.DS;
        if (!file_exists($tmpDir)) { mkdir ($tmpDir, 0777, true); }
        return file_put_contents($tmpDir.'hosts', $this->hostFileData);
    }

    private function moveHostFileAsRoot(){
        $command = 'sudo mv '.$this->baseTempDir.DS.'hostfile'.DS.'hosts '.DS.'etc'.DS.'hosts';
        self::executeAndOutput($command);
        $command = 'sudo rm -rf '.$this->baseTempDir.DS.'hostfile';
        self::executeAndOutput($command);
    }

    private function hostFileDataAdd($ipEntry, $uri){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $hostFileLines = explode("\n", $this->hostFileData) ;
        $newHostFileData = "";
        $logging->log("Attempting to add Host File Entry...", $this->getModuleName()) ;
        foreach ($hostFileLines as $line) {
            $ipOccurs = substr_count($line, $ipEntry) ;
            $uriOccurs = substr_count($line, $uri) ;
            $bothOccur = ( $ipOccurs==1 && $uriOccurs==1);
            if ( $bothOccur )  {
                $logging->log("Host file entry already exists for Host Name {$uri}, with IP {$ipEntry} no need to edit...", $this->getModuleName()) ;
                return true; }
            else if ( $uriOccurs )  {
                $logging->log("Host file entry already exists for Host Name {$uri}, with IP {$ipEntry} removing...", $this->getModuleName()) ;
                continue ; }
            else {
                $newHostFileData .= $line."\n"; } }
        $logging->log("Adding requested entry {$uri}, with IP {$ipEntry} to host file data", $this->getModuleName()) ;
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
            if (isset($this->params["guess"])) {
                if ($uriOccurs) { continue ; } }
            if ( !$bothOccur )  { $newHostFileData .= $line."\n"; } }
        $this->hostFileData = $newHostFileData;
        $this->deleteHostFileEntryFromProjectFile();
    }

    private function writeHostFileEntryToProjectFile(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $projectFactory = new \Model\Project();
        $projectModel = $projectFactory->getModel($this->params);
        if ($projectModel::checkIsPharaohProject()) {
            $appSettingsFactory = new \Model\AppSettings();
            $appConfig = $appSettingsFactory->getModel($this->params, "AppConfig") ;
            $logging->log("Attempting to write host entry to project file, {$this->uri} {$this->ipAddress}...") ;
            $appConfig::setProjectVariable("host-entries", array("$this->uri" => "$this->ipAddress", true) );  }
    }

    private function deleteHostFileEntryFromProjectFile(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $projectFactory = new \Model\Project();
        $projectModel = $projectFactory->getModel($this->params);
        if ($projectModel::checkIsPharaohProject()) {
            $appSettingsFactory = new \Model\AppSettings();
            $appConfig = $appSettingsFactory->getModel($this->params, "AppConfig") ;
            $allHostFileEntries = $appConfig::getProjectVariable("host-entries");
            if ($allHostFileEntries instanceof \stdClass) { $allHostFileEntries = new \ArrayObject($allHostFileEntries); }
            for ($i = 0; $i<=count($allHostFileEntries) ; $i++ ) {
                if (isset($allHostFileEntries[$i]) && is_array($allHostFileEntries[$i]) && array_key_exists($this->uri, $allHostFileEntries[$i])) {
                    $logging->log("Attempting to remove host entry from project file, {$this->uri}...") ;
                    unset($allHostFileEntries[$i]); } }
            $appConfig::setProjectVariable("host-entries", $allHostFileEntries); }
    }

}