<?php

Namespace Model;

// @todo this class is way too long, we should use model groups, at least for balancing
// @todo  the vhosttemp folder that gets left in temp should be removed
class ApacheVHostEditorCentos extends ApacheVHostEditorUbuntu {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("RedHat") ;
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

}