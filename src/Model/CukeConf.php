<?php

Namespace Model;

class HostEditor extends Base {

    private $hostFileData;

    public function askWhetherToDoHostEntry(){
        $this->performHostEditing();
    }

    private function performHostEditing(){
        $hostFileEntry = $this->askForHostEntryToScreen();
        if (!$hostFileEntry) { return false; }
        $ipEntry = $this->askForIPEntryToScreen();
        if ($ipEntry=="") {$ipEntry="127.0.0.1";}
        $uri = $this->askForHostfileUri();
        $this->loadCurrentHostFile();
        $this->hostFileDataChange($ipEntry, $uri);
        $this->checkHostFileOkay();
        $this->createHostFile();
        $this->moveVHostAsRoot();
    }

    private function askForHostEntryToScreen(){
        $question = 'Do you want to add a hosts file entry? (Y/N)';
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
        $question = 'Please check host file: '.$this->hostFileData."\n\nIs this Okay? () Y/N";
        return self::askYesOrNo($question);
    }

    private function createHostFile() {
        $tmpDir = '/tmp/hostfile/';
        if (!file_exists($tmpDir)) { mkdir ($tmpDir); }
        return file_put_contents($tmpDir.'/hosts', $this->hostFileData);
    }

    private function moveVHostAsRoot(){
        $command = 'sudo mv /tmp/hostfile/hosts /etc/hosts';
        self::executeAndOutput($command);
    }

    private function hostFileDataChange($ipEntry, $uri){
        $this->hostFileData .= "\n$ipEntry          $uri";
    }

}