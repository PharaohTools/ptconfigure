<?php

Namespace Model;

class JavaCentos64 extends JavaUbuntu64 {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("64") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy java" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy java" ;
        $this->initialize();
    }

}
