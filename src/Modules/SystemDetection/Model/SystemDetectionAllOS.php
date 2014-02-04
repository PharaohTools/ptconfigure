<?php

Namespace Model;

class SystemDetectionAllOS extends Base {

    public $os ; // = array("any", "Linux", "Windows", "MacOS") ;
    public $linuxType ; // = array("any", "Debian", "Redhat") ;
    public $distro ; // = array("any", "Ubuntu", "Arch", "Debian", "Fedora", "CentOS") ; @todo add suse, mandriva
    public $version ; // = array("any", "11.04", "11.10", "12.04", "13.04") ; @todo win7, win2003, etc
    public $architecture ; // = array("any", "32", "64" ;
    public $hostName ; // = array("any", "32", "64" ;
    public $ipAddresses = array();

    public function __construct() {
        $this->setOperatingSystem();
        $this->setDistro();
        $this->setLinuxType();
        $this->setVersion();
        $this->setArchitecture();
        $this->setHostname();
        $this->setIPAddresses();
    }

    private function setOperatingSystem() {
        $this->os = PHP_OS ;
    }

    private function setDistro() {
        switch ($this->os) {
            case "Linux" :
                $this->distro = $this->getLinuxDistro() ;
                break ;
            case "Darwin" :
                $this->distro = $this->getMacDistro() ;
                break ;
        }
    }

    private function setLinuxType() {
        if (in_array($this->distro, array("Ubuntu", "Debian"))) {
            $this->linuxType = "Debian" ; }
        else if (in_array($this->distro, array("CentOS", "Redhat"))) {
            $this->linuxType = "Redhat" ; }
        else {
            $this->linuxType = "None" ; }
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


    private function getMacDistro() {
        //declare Mac distros(extensible list).
        $distros = array(
            "10.0" => "Cheetah",
            "10.1" => "Puma",
            "10.2" => "Jaguar",
            "10.3" => "Panther",
            "10.4" => "Tiger",
            "10.5" => "Leopard",
            "10.6" => "Snow Leopard",
            "10.7" => "Lion",
            "10.8" => "Mountain Lion",
            "10.9" => "Mavericks",
        );
        $majorVersion = substr($this->version, 0, 4) ;
        return $distros[$majorVersion];
    }

    private function setVersion() {
        if ($this->os == "Linux") {
            if (in_array($this->distro, array("Ubuntu")) ) {
                exec("lsb_release -a 2> /dev/null", $output_array);
                $this->version = substr($output_array[2], 9) ; }
            if (in_array($this->distro, array("CentOS")) ) {
                exec("cat /etc/centos-release", $output_array);
                $this->version = substr($output_array[0], 15, 3) ; } }
        if ($this->os == "Darwin") {
            exec("sw_vers | grep 'ProductVersion:' | grep -o '[0-9]*\.[0-9]*\.[0-9]*'", $output_array);
            $this->version = $output_array[0] ; }
    }

    private function setArchitecture() {
        if(($this->os == "Linux" && in_array($this->distro, array("Ubuntu", "CentOS")) || 
	        $this->os == "Darwin")) {
	        $output = exec("arch");
            if (strpos($output, "x86_64") !== false ) {
                $this->architecture = "64" ; }
            if (strpos($output, "i386") !== false ) {
                $this->architecture = "32" ; }
            if (strpos($output, "i686") !== false ) {
                $this->architecture = "32" ; } }
    }

    private function setHostname() {
        if ($this->os == "Linux" || $this->os == "Darwin") {
            exec("hostname", $output_array);
            $this->hostName = $output_array[0] ; }
    }

    private function setIPAddresses() {
        if ($this->os == "Linux") {
            $ifComm = "sudo ifconfig  | grep 'inet addr:'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1}'" ;
            exec($ifComm, $outputArray);
            foreach($outputArray as $outputLine ) {
                $this->ipAddresses[] = $outputLine ; } }
        else if ($this->os == "Solaris") {
            $ifComm = "sudo ifconfig  | grep 'inet addr:'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1}'" ;
            exec($ifComm, $outputArray);
            foreach($outputArray as $outputLine ) {
                $this->ipAddresses[] = $outputLine ; } }
        else if (in_array($this->os, array("FreeBSD", "OpenBSD", "Darwin"))) {
            $ifComm = "sudo ifconfig  | grep -E 'inet.[0-9]' | grep -v '127.0.0.1' | awk '{ print $2}'" ;
            exec($ifComm, $outputArray);
            foreach($outputArray as $outputLine ) {
                $this->ipAddresses[] = $outputLine ; } }

    }

}
