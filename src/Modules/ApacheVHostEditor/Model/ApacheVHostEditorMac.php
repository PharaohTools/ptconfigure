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
        if ($this->detectMacVHostFolderExistence()) { $question .= ' Found "/etc/httpd/vhosts.d" - Enter nothing to use this';
            if (isset($this->params["guess"])) { return "/etc/httpd/vhosts.d" ; }
            $input = self::askForInput($question);
            return ($input=="") ? "/etc/httpd/vhosts.d" : $input ;  }
        return self::askForInput($question, true);
    }

    protected function detectMacVHostFolderExistence(){
        return file_exists("/etc/httpd/vhosts.d");
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


}