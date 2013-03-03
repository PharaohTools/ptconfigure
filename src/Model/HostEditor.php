<?php

Namespace Model;

class HostEditor extends Base {

    private $hostFileData;

    public function askWhetherToDoHostEntry(){
        return $this->performHostAddition();
    }

    public function askWhetherToDoHostRemoval(){
        return $this->performHostDeletion();
    }

    private function performHostAddition(){
        $hostFileEntry = $this->askForHostEntryToScreen();
        if (!$hostFileEntry) { return false; }
        $ipEntry = $this->askForIPEntryToScreen();
        if ($ipEntry=="") {$ipEntry="127.0.0.1";}
        $uri = $this->askForHostfileUri();
        $this->loadCurrentHostFile();
        $this->hostFileDataAdd($ipEntry, $uri);
        $this->checkHostFileOkay();
        $this->createHostFile();
        $this->moveHostFileAsRoot();
        return true;
    }

    private function performHostDeletion(){
        $hostFileDel = $this->askForHostDeletionToScreen();
        if (!$hostFileDel) { return false; }
        $ipEntry = $this->askForIPEntryToScreen();
        if ($ipEntry=="") {$ipEntry="127.0.0.1";}
        $uri = $this->askForHostfileUri();
        $this->loadCurrentHostFile();
        $this->hostFileDataRemove($ipEntry, $uri);
        $this->checkHostFileOkay();
        $this->createHostFile();
        $this->moveHostFileAsRoot();
        return true;
    }

    public function runAutoPilotHostAddition($autoPilot){
        $hostFileEntry = $autoPilot->hostEditorAdditionExecute;
        if (!$hostFileEntry) { return false; }
        $ipEntry = $autoPilot->hostEditorAdditionIP;
        $uri = $autoPilot->hostEditorAdditionURI;
        $this->loadCurrentHostFile();
        $this->hostFileDataAdd($ipEntry, $uri);
        $this->createHostFile();
        $this->moveHostFileAsRoot();
        return true;
    }

    public function runAutoPilotHostDeletion($autoPilot){
        $hostFileEntry = $autoPilot->hostEditorDeletionExecute;
        if (!$hostFileEntry) { return false; }
        $ipEntry = $autoPilot->hostEditorDeletionIP;
        $uri = $autoPilot->hostEditorDeletionURI;
        $this->loadCurrentHostFile();
        $this->hostFileDataRemove($ipEntry, $uri);
        $this->createHostFile();
        $this->moveHostFileAsRoot();
        return true;
    }

    private function askForHostEntryToScreen(){
        $question = 'Do you want to add a hosts file entry?';
        return self::askYesOrNo($question);
    }

    private function askForHostDeletionToScreen(){
        $question = 'Do you want to remove a hosts file entry?';
        return self::askYesOrNo($question);
    }

    private function askForIPEntryToScreen(){
        $question = 'Do you want a non-default IP? Enter for 127.0.0.1';
        return self::askForInput($question);
    }

    private function askForHostfileUri(){
        $question = 'What URI do you want to add to the hostfile?';
        return self::askForInput($question, true);
    }

    private function loadCurrentHostFile() {
        $command = 'sudo cat /etc/hosts';
        $this->hostFileData = self::executeAndLoad($command);
    }

    private function checkHostFileOkay(){
        $question = 'Please check host file: '.$this->hostFileData."\n\nIs this Okay? ";
        return self::askYesOrNo($question);
    }

    private function createHostFile() {
        $tmpDir = '/tmp/hostfile/';
        if (!file_exists($tmpDir)) { mkdir ($tmpDir); }
        return file_put_contents($tmpDir.'/hosts', $this->hostFileData);
    }

    private function moveHostFileAsRoot(){
        $command = 'sudo mv /tmp/hostfile/hosts /etc/hosts';
        self::executeAndOutput($command);
    }

    private function hostFileDataAdd($ipEntry, $uri){
        $this->hostFileData .= "\n$ipEntry          $uri";
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
    }

}