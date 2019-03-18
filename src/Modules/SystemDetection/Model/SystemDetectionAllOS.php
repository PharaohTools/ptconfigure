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
    private static $preset_version ;
    private static $preset_ips ;
    private static $preset_arch ;
    private static $preset_hostname ;

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
        if ($this->os == "Linux" ) {
            $this->distro = $this->getLinuxDistro() ; }
        else if ($this->os == "Darwin"  ) {
            $this->distro = $this->getMacDistro() ; }
        else {
            $this->distro = "None" ; }
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
        // @todo maybe we should set no distro if we cant find one? guessing this might mess up
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
            "10.10" => "Yosemite",
        );
        $majorVersion = substr($this->version, 0, 4) ;
        if (isset($distros[$majorVersion])) {
            return $distros[$majorVersion]; }
        else {
            $keys = array_keys($distros);
            $count = count($keys)-1 ;
            $v = $distros[$keys[$count]] ;
            return $v ; }
    }

    private function setVersion() {
        if (isset(self::$preset_version)) {
            $this->version = self::$preset_version ;
            return ;
        }
        if ($this->os == "Linux") {
            if (in_array($this->distro, array("Ubuntu")) ) {
                $vars = [] ;
                $data = file_get_contents('/etc/lsb-release') ;
                $lines = explode("\n", $data) ;
                foreach ($lines as $line) {
                    $parts = explode('=', $line) ;
                    if (isset($parts[0]) && isset($parts[1])) {
                        $vars[$parts[0]] = $parts[1] ;
                    }
                }
                if (isset($vars['DISTRIB_RELEASE'])) {
                    $this->version = $vars['DISTRIB_RELEASE'] ;
                } else {
                    exec("lsb_release -a 2> /dev/null", $output_array);
                    $this->version = substr($output_array[2], 9) ;
                } }
            if (in_array($this->distro, array("CentOS")) ) {
                exec("cat /etc/*-release", $output_array);
                $this->version = substr($output_array[0], 15, 3) ; } }
        else if ($this->os == "Darwin") {
            exec("sw_vers | grep 'ProductVersion:' | grep -o '[0-9]*\.[0-9]*\.[0-9]*'", $output_array);
            $this->version = $output_array[0] ; }
        else if (in_array($this->os, array("Windows", "WINNT"))) {
            exec("ver", $output_array);
            $verString = substr(
                $output_array[1],
                (strpos($output_array[1], "[Version ")+9),
                ((strlen($output_array[1])-1) - (strpos($output_array[1], "[Version ")+9))
            ) ;
            $versionObject = new \Model\SoftwareVersion($verString) ;
            $this->version =  $versionObject->fullVersionNumber; }
        self::$preset_version = $this->version ;
    }

    private function setArchitecture() {
        if (isset(self::$preset_arch)) {
            $this->architecture = self::$preset_arch ;
            return ;
        }
        if(($this->os == "Linux" && in_array($this->distro, array("Ubuntu", "CentOS")) ||
            $this->os == "Darwin")) {
            $output = exec("arch");
            if (strpos($output, "x86_64") !== false ) {
                $this->architecture = "64" ; }
            if (strpos($output, "i386") !== false ) {
                $this->architecture = "32" ; }
            if (strpos($output, "i686") !== false ) {
                $this->architecture = "32" ; } }
        else if (in_array($this->os, array("Windows", "WINNT"))) {
            $output = self::executeAndLoad("wmic OS get OSArchitecture");
            if (strpos($output, "64") !== false ) {
                $this->architecture = "64" ; }
            else {
                $this->architecture = "32" ; } }
        self::$preset_arch = $this->architecture ;
    }

    private function setHostname() {
        if (isset(self::$preset_hostname)) {
            $this->hostName = self::$preset_hostname ;
            return ;
        }
        if (in_array($this->os, array("Windows", "WINNT", "Darwin", "Linux"))) {
            exec("hostname", $output_array);
            $this->hostName = $output_array[0] ; }
        self::$preset_hostname = $this->hostName ;
    }

    private function setIPAddresses() {
        if (isset(self::$preset_ips)) {
            $this->ipAddresses = self::$preset_ips ;
            return ;
        }
        if ($this->os == "Linux") {
            if ($this->distro == "CentOS") {
                // Centos has no network tools at all for some crazy reason, install them now
                // @todo surely captain, there must be a better way
                exec('yum install net-tools -y', $outputArray);
            }
            $ifComm = SUDOPREFIX.' ip addr list | awk \'/inet /{sub(/\/[0-9]+/,"",$2); print $2}\' ';
            // $ifComm = "sudo ifconfig  | grep 'inet addr:'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1}'" ;
            exec($ifComm, $outputArray);
            foreach($outputArray as $outputLine ) {
                $this->ipAddresses[] = $outputLine ; } }
        else if ($this->os == "Solaris") {
            $ifComm = SUDOPREFIX." ifconfig  | grep 'inet addr:'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1}'" ;
            exec($ifComm, $outputArray);
            foreach($outputArray as $outputLine ) {
                $this->ipAddresses[] = $outputLine ; } }
        else if (in_array($this->os, array("FreeBSD", "OpenBSD"))) {
            $ifComm = SUDOPREFIX." ifconfig  | grep -E 'inet.[0-9]' | grep -v '127.0.0.1' | awk '{ print $2}'" ;
            exec($ifComm, $outputArray);
            foreach($outputArray as $outputLine ) {
                $this->ipAddresses[] = $outputLine ; } }
        else if (in_array($this->os, array("Darwin"))) {
                $ifComm = SUDOPREFIX." /sbin/ifconfig  | grep -E 'inet.[0-9]' | grep -v '127.0.0.1' | awk '{ print $2}'" ;
                exec($ifComm, $outputArray);
                foreach($outputArray as $outputLine ) {
                    $this->ipAddresses[] = $outputLine ; } }
        self::$preset_ips = $this->ipAddresses ;
    }

}
