<?php

Namespace Model;

class ApacheVHostEditorMac extends ApacheVHostEditorCentos {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected function askForVHostDirectory(){
        if (isset($this->params["vhe-vhost-dir"])) { return $this->params["vhe-vhost-dir"] ; }
        $question = 'What is your VHost directory?';
        if ($this->detectMacVHostFolderExistence()) { $question .= ' Found "/etc/apache2/other" - Enter nothing to use this';
            if (isset($this->params["guess"])) { return "/etc/apache2/other" ; }
            $input = self::askForInput($question);
            return ($input=="") ? "/etc/apache2/other" : $input ;  }
        return self::askForInput($question, true);
    }

    protected function detectMacVHostFolderExistence(){
        return file_exists("/etc/apache2/other");
    }


    protected function enableVHost(){
        if (isset($this->params["vhe-file-ext"]) && strlen($this->params["vhe-file-ext"])>0 ) {
            // @todo this with mv a2ensite doesnt work, this will also only work with an include dir
//            $command = 'a2ensite '.$this->url.$this->params["vhe-file-ext"];
        }
        else {
            // @todo this with mv a2ensite doesnt work, this will also only work with an include dir
//            $command = 'a2ensite '.$this->url ;
}
//        return self::executeAndOutput($command, "a2ensite $this->url done");
        return true ;
    }

    protected function disableVHost(){
        if (!is_array($this->vHostForDeletion)) {
            $this->vHostForDeletion = array($this->vHostForDeletion) ; }
        foreach ($this->vHostForDeletion as $vHost) {
            // @todo this with mv a2ensite doesnt work, this will also only work with an include dir
//            $command = 'a2dissite '.$vHost;
//            self::executeAndOutput($command, "a2dissite $vHost done");
        }
        return true;
    }

    protected function askForFileExtension() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Setting a file extension for OSX?", $this->getModuleName()) ;
        if (isset($this->params["vhe-file-ext"])) { return $this->params["vhe-file-ext"] ; }
        if (isset($this->params["guess"])) {
            $logging->log("Guessing your VHost on OSX uses a .conf extension", $this->getModuleName()) ;
            return ".conf" ; }
        $question = 'What File Extension should be used? Enter nothing for None (probably .conf on this system)';
        $input = self::askForInput($question) ;
        return $input ;
    }


}