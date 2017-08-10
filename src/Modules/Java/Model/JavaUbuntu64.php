<?php

Namespace Model;

class JavaUbuntu64 extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("64") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $javaDetails ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Java";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForJavaInstallVersion", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForJavaInstallDirectory", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "runJavaInstall", "params" => array()) ),
        );

        //@todo uninstall commands of java
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForJavaInstallDirectory", "params" => array()) ),);
        $this->programDataFolder = "/var/lib/jvm/jdk".$this->params["java-install-version"];
        $this->programNameMachine = "java"; // command and app dir name
        $this->programNameFriendly = "!!Java JDK!!"; // 12 chars
        $this->programNameInstaller = "The Oracle Java JDK";
        $this->statusCommand = 'java -version' ;
        $this->versionInstalledCommand = 'java -version 2>&1' ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy java" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy java" ;
        $this->initialize();
    }

    protected function askForJavaInstallVersion() {
        if (isset($this->params["java-install-version"])) {
            $this->javaDetails = $this->getJavaDetails($this->params["java-install-version"]); }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->params["java-install-version"] = "1.7" ;
            $this->javaDetails = $this->getJavaDetails("1.7") ; }
        else {
            $question = "Enter Java Install Version (1.7 or 1.8):";
            $jd = self::askForInput($question, true);
            $this->params["java-install-version"] = $jd ;
            $this->javaDetails = $this->getJavaDetails($jd); }
    }

    protected function runJavaInstall() {
        $ray =
            array(
                array("command" => array(
                    "if [ ! -f /tmp/oraclejdk.tar.gz ] ; then curl -o /tmp/oraclejdk.tar.gz {$this->javaDetails['jdk_url']} ; fi" ,
                    "mkdir -p /tmp/oraclejdk",
                    "tar -xzf /tmp/oraclejdk.tar.gz -C /tmp/oraclejdk",
                    "rm -f /tmp/oraclejdk.tar.gz",
                    "mkdir -p ****PROGDIR****" ,
                    "rm -rf ****PROGDIR****/*" ,
//                    "apt-get install libc6-i386" ,
                    "cp -r /tmp/oraclejdk/{$this->javaDetails['extracted_dir']}/* ****PROGDIR****" ,
                    "rm -rf /tmp/oraclejdk" ,
                    "cd ****PROGDIR****",
                    "chmod a+x ****PROGDIR****",
                    'echo \'JAVA_HOME=****PROGDIR****\' >> /etc/profile',
                    'echo \'PATH=$PATH:$HOME/bin:$JAVA_HOME/bin\' >> /etc/profile',
                    'echo \'export JAVA_HOME\' >> /etc/profile',
                    'echo \'export PATH\' >> /etc/profile',
                    'echo \'JAVA_HOME=****PROGDIR****\' >> /etc/bash.bashrc',
                    'echo \'PATH=$PATH:$HOME/bin:$JAVA_HOME/bin\' >> /etc/bash.bashrc',
                    'echo \'export JAVA_HOME\' >> /etc/bash.bashrc',
                    'echo \'export PATH\' >> /etc/bash.bashrc',
                    SUDOPREFIX.'update-alternatives --install "/usr/bin/java" "java" "****PROGDIR****/bin/java" 1 ',
                    SUDOPREFIX.'update-alternatives --install "/usr/bin/javac" "javac" "****PROGDIR****/bin/javac" 1 ',
                    SUDOPREFIX.'update-alternatives --install "/usr/bin/javaws" "javaws" "****PROGDIR****/bin/javaws" 1 ',
                    SUDOPREFIX.'update-alternatives --set java ****PROGDIR****/bin/java ',
                    SUDOPREFIX.'update-alternatives --set javac ****PROGDIR****/bin/javac ',
                    SUDOPREFIX.'update-alternatives --set javaws ****PROGDIR****/bin/javaws ',
                    '. /etc/profile' )
                )
            ) ;
        $this->installCommands = $ray ;
        return $this->doInstallCommand() ;
    }

    public function getJavaDetails($version) {
        if ($version == "1.8") {
            $details['jdk_url'] = "http://46f95a86014936ec1625-77a12a9c8b6f69dd83500dbd082befcc.r16.cf3.rackcdn.com/jdk1.8x64.tar.gz" ;
            $details['path_in_repo'] = "phpengine-cleo-jdk-64-6c383e2868bd/jdk-7u60-linux-x64.tar.gz" ;
            $details['fname_in_repo'] = "jdk-7u60-linux-x64.tar.gz" ;
            $details['extracted_dir'] = "jdk1.8.0_144" ;
        } else {
            $details['jdk_url'] = "http://46f95a86014936ec1625-77a12a9c8b6f69dd83500dbd082befcc.r16.cf3.rackcdn.com/jdk1.7.tar.gz" ;
            $details['path_in_repo'] = "jdk-7u60-linux-x64.tar.gz" ;
            $details['fname_in_repo'] = "jdk-7u60-linux-x64.tar.gz" ;
            $details['extracted_dir'] = "jdk1.7.0_60" ;
        }
        return $details ;
    }

    protected function askForJavaInstallDirectory() {
        if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            return; }
        else if (isset($this->params["java-install-dir"])) {
            $this->programDataFolder = $this->params["java-install-dir"]; }
        else {
            $question = "Enter Java Install Directory (no trailing slash):";
            $this->programDataFolder = self::askForArrayOption($question, array("1.7", "1.8"), true); }
    }

    public function versionInstalledCommandTrimmer($text) {
        $leftQuote = strpos($text, 'java version "') + 14 ;
        $rightQuote = strpos($text, '"', $leftQuote) ;
        $difference = $rightQuote - $leftQuote ;
        $done = substr($text, $leftQuote, $difference) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

}
