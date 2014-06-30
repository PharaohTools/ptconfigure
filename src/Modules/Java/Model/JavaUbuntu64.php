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

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Java";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForJavaInstallDirectory", "params" => array()) ),
            array("command" => array(
                "wget https://bitbucket.org/phpengine/cleo-jdk-64/get/6c383e2868bd.zip /tmp/oraclejdk" ,
                "mv 6c383e2868bd.zip /tmp/oraclejdk.zip",
                "cd /tmp",
                "unzip /tmp/oraclejdk.zip -d /tmp/oraclejdk",
                "rm -f /tmp/oraclejdk.zip",
                "cd /tmp/oraclejdk" ,
                "mv /tmp/oraclejdk/phpengine-cleo-jdk-64-6c383e2868bd/jdk-7u60-linux-x64.tar.gz /tmp/oraclejdk" ,
                "tar -xf jdk-7u60-linux-x64.tar.gz" ,
                "mkdir -p ****PROGDIR****" ,
                "cp -r /tmp/oraclejdk/jdk1.7.0_60/* ****PROGDIR****" ,
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
                'sudo update-alternatives --install "/usr/bin/java" "java" "****PROGDIR****/bin/java" 1 ',
                'sudo update-alternatives --install "/usr/bin/javac" "javac" "****PROGDIR****/bin/javac" 1 ',
                'sudo update-alternatives --install "/usr/bin/javaws" "javaws" "****PROGDIR****/bin/javaws" 1 ',
                'sudo update-alternatives --set java ****PROGDIR****/bin/java ',
                'sudo update-alternatives --set javac ****PROGDIR****/bin/javac ',
                'sudo update-alternatives --set javaws ****PROGDIR****/bin/javaws ',
                '. /etc/profile' ) )
            );
        //@todo uninstall commands of java
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForJavaInstallDirectory", "params" => array()) ),);
        $this->programDataFolder = "/var/lib/jvm/jdk1.7";
        $this->programNameMachine = "java"; // command and app dir name
        $this->programNameFriendly = "!!Java JDK!!"; // 12 chars
        $this->programNameInstaller = "The Oracle Java JDK 1.7";
        $this->versionInstalledCommand = 'java -version 2>&1' ;
        $this->versionRecommendedCommand = "sudo apt-cache policy java" ;
        $this->versionLatestCommand = "sudo apt-cache policy java" ;
        $this->initialize();
    }

    protected function askForJavaInstallDirectory() {
        if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            return; }
        else if (isset($this->params["java-install-dir"])) {
            $this->programDataFolder = $this->params["java-install-dir"]; }
        else {
            $question = "Enter Java Install Directory (no trailing slash):";
            $this->programDataFolder = self::askForInput($question, true); }
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
