<?php

Namespace Model;

class HostEditor extends Base {

    public function askWhetherToDoHostEntry(){
        $this->performHostEditing();
    }

    private function performHostEditing(){
        $hostFileEntry = $this->askForHostEntryToScreen();
        if (!$hostFileEntry) { return false; }
        $ipEntry = $this->askForIPEntryToScreen();
        if ($ipEntry=="") {$ipEntry="127.0.0.1";}
        $uri = $this->askForHostfileUri();
        $this->attemptFileChange($ipEntry, $uri);
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

    private function attemptFileChange($ipEntry, $uri){
        $isWritable = $this->checkHostIsWritable();
        if ($isWritable) { $this->doFileChange($ipEntry, $uri); }
        else {
            $this->switchToRoot();
            $this->doFileChange($ipEntry, $uri);
            $this->exitRoot(); }
    }

    private function switchToRoot(){
        $command = 'sudo su root';
        return self::executeAndOutput($command);
    }

    private function exitRoot(){
        $command = 'exit';
        return self::executeAndOutput($command);
    }

    private function checkHostIsWritable(){
        return is_writable('/etc/hosts');
    }

    private function doFileChange($ipEntry, $uri){
        $command = 'echo "'.$ipEntry.'         '.$uri.'" >> /etc/hosts';
        return self::executeAndOutput($command);
    }

}