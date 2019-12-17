<?php

Namespace Model;

class ApacheVHostEditorCentos extends ApacheVHostEditorUbuntuLegacy {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("CentOS") ;
    public $versions = array(array("6", "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params) ;
    }

    protected function performVHostEnable() {
        if ( $this->askForEnableVHost() ) {
            $urlRay = $this->selectVHostInProjectOrFS() ;
            $this->url = $urlRay[0] ;
            $this->enableVHost(); }
        return true;
    }

    protected function performVHostDisable(){
        if ( $this->askForDisableVHost() ) {
            $this->vHostForDeletion = $this->selectVHostInProjectOrFS();
            $this->disableVHost(); }
        return true;
    }

    protected function askForFileExtension() {
        if (isset($this->params["vhe-file-ext"])) { return $this->params["vhe-file-ext"] ; }
        if (isset($this->params["guess"])) { return "" ; }
        $question = 'What File Extension should be used? Enter nothing for None (probably .conf on this system)';
        $input = self::askForInput($question) ;
        return $input ;
    }

    protected function askForVHostDirectory(){
        if (isset($this->params["vhe-vhost-dir"])) { return $this->params["vhe-vhost-dir"] ; }
        $question = 'What is your VHost directory?';
        if ($this->detectRHVHostFolderExistence()) { $question .= ' Found "/etc/httpd/vhosts.d" - Enter nothing to use this';
            if (isset($this->params["guess"])) { return "/etc/httpd/vhosts.d" ; }
            $input = self::askForInput($question);
            return ($input=="") ? "/etc/httpd/vhosts.d" : $input ;  }
        return self::askForInput($question, true);
    }

}