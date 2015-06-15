<?php

Namespace Model;

class PearCentos extends PearUbuntu {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $packagerName = "Pear";

    public $actionsToMethods =
        array(
            "pkg-install" => "performInstall",
            "pkg-ensure" => "performInstall",
            "pkg-remove" => "performRemove",
            "pkg-exists" => "performExistenceCheck",
            "channel-discover" => "channelDiscover",
            "channel-delete" => "channelDelete",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        // @todo yum version
        $this->versionInstalledCommand = "sudo apt-cache policy php-pear" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy php-pear" ;
        $this->versionLatestCommand = "sudo apt-cache policy php-pear" ;
        $this->initialize();
    }

}
