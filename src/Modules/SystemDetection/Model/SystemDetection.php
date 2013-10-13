<?php

Namespace Model;

class SystemDetection {

    public $os ; // = array("any", "Linux", "Windows", "MacOS") ;
    public $linuxType ; // = array("any", "Debian", "Redhat") ;
    public $distro ; // = array("any", "Ubuntu", "Arch", "Debian", "Fedora", "CentOS") ; @todo add suse, mandriva
    public $version ; // = array("any", "11.04", "11.10", "12.04", "13.04") ; @todo win7, win2003, etc
    public $architecture ; // = array("any", "32", "64" ;

    public function __construct() {
        $this->setOperatingSystem();
        $this->setDistro();
        $this->setLinuxType();
        $this->setVersion();
        $this->setArchitecture();
    }

    private function setOperatingSystem() {
        $this->os = PHP_OS ;
    }

    private function setDistro() {
        $this->distro = $this->getLinuxDistro() ;
    }

    private function setLinuxType() {
        if (in_array($this->distro, array("Ubuntu", "Debian"))) {
            $this->linuxType = "Debian" ; }
        else if (in_array($this->distro, array("CentOS", "Redhat"))) {
            $this->linuxType = "Redhat" ; }
    }

    private function getLinuxDistro() {
        //declare Linux distros(extensible list).
        $distros = array(
            "Arch" => "arch-release",
            'CentOS' => 'centos-release',
            'Redhat' => 'redhat-release',
            "Ubuntu" => "lsb-release",
            "Debian" => "debian_version",
            "Fedora" => "fedora-release",);
        //Get everything from /etc directory.
        $etcList = scandir('/etc');
        $OSDistro = "";
        //Loop through list of distros..
        foreach ($distros as $distroTitle => $distroReleaseFile) {
            //Loop through /etc results...
            foreach ($etcList as $entry) {
                //Match was found.
                if ($distroReleaseFile === $entry) {
                    //Find distros array key(i.e. Distro name) by value(i.e. distro release file)
                    $OSDistro = $distroTitle; // array_search($distroReleaseFile, $distros);
                    //Break inner and outer loop.
                    break 2; } } }
        return $OSDistro;
    }

    private function setVersion() {
        if ($this->os = "Linux") {
            if (in_array($this->distro, array("Ubuntu")) ) {
                exec("lsb_release -a 2> /dev/null", $output_array);
                $this->version = substr($output_array[2], 9) ; }
            if (in_array($this->distro, array("CentOS")) ) {
                exec("cat /etc-centos", $output_array);
                $this->version = substr($output_array[0], 15, 3) ; } }
    }

    private function setArchitecture() {
        if ($this->os = "Linux") {
            if (in_array($this->distro, array("Ubuntu")) ) {
                $output = exec("arch");
                if (strpos($output, "x86_64") !== false ) {
                    $this->architecture = "64" ; }
                if (strpos($output, "i386") !== false ) {
                    $this->architecture = "32" ; }
                if (strpos($output, "i686") !== false ) {
                    $this->architecture = "32" ; } }
            if (in_array($this->distro, array("CentOS")) ) {
                $output = exec("arch");
                if (strpos($output, "x86_64") !== false ) {
                    $this->architecture = "64" ; }
                if (strpos($output, "i386") !== false ) {
                    $this->architecture = "32" ; }
                if (strpos($output, "i686") !== false ) {
                    $this->architecture = "32" ; } } }
    }

}