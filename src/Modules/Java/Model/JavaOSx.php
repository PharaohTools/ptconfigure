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
        $this->installCommands = $this->getInstallCommands() ;
        //@todo uninstall commands of java
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForJavaInstallDirectory", "params" => array()) ),);
        $this->programDataFolder = "/var/lib/jvm/jdk1.7";
        $this->programNameMachine = "java"; // command and app dir name
        $this->programNameFriendly = "!!Java JDK!!"; // 12 chars
        $this->programNameInstaller = "The Oracle Java JDK 1.7";
        $this->statusCommand = 'java -version' ;
        $this->versionInstalledCommand = 'java -version 2>&1' ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy java" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy java" ;
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

    // @todo this should definitely be using a package manager module
    protected function getInstallCommands() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $ray = array(
            array("method"=> array("object" => $this, "method" => "askForJavaInstallDirectory", "params" => array()) ),
            array("command" => array(
                "curl -o /tmp/oraclejdk.zip http://46f95a86014936ec1625-77a12a9c8b6f69dd83500dbd082befcc.r16.cf3.rackcdn.com/jdk-17.zip" ,
                // "mv /tmp/6c383e2868bd.zip /tmp/oraclejdk.zip",
                "unzip /tmp/oraclejdk.zip -d /tmp/oraclejdk",
                "rm -f /tmp/oraclejdk.zip",
                "mv /tmp/oraclejdk/phpengine-cleo-jdk-64-6c383e2868bd/jdk-7u60-linux-x64.tar.gz /tmp/oraclejdk" ,
                "cd /tmp/oraclejdk && tar -xf jdk-7u60-linux-x64.tar.gz" ,
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
                SUDOPREFIX.'update-alternatives --install "/usr/bin/java" "java" "****PROGDIR****/bin/java" 1 ',
                SUDOPREFIX.'update-alternatives --install "/usr/bin/javac" "javac" "****PROGDIR****/bin/javac" 1 ',
                SUDOPREFIX.'update-alternatives --install "/usr/bin/javaws" "javaws" "****PROGDIR****/bin/javaws" 1 ',
                SUDOPREFIX.'update-alternatives --set java ****PROGDIR****/bin/java ',
                SUDOPREFIX.'update-alternatives --set javac ****PROGDIR****/bin/javac ',
                SUDOPREFIX.'update-alternatives --set javaws ****PROGDIR****/bin/javaws ',
                '. /etc/profile' ) )
        );
        return $ray ;


        $dmgFile = BASE_TEMP_DIR."virtualbox.dmg" ;
        $ray = array(
            array("command" => array( SUDOPREFIX."rm -rf $dmgFile") ),
            array("command" => array( 'curl "http://download.virtualbox.org/virtualbox/5.1.22/VirtualBox-5.1.22-115126-OSX.dmg" -o "'.$dmgFile.'"') ),
            array("command" => array( SUDOPREFIX."hdiutil attach $dmgFile") ),
            array("command" => array( SUDOPREFIX.'installer -pkg /Volumes/VirtualBox/VirtualBox.pkg -target /') ),
            array("method"=> array("object" => $this, "method" => "ensureDefaultHostOnlyNetwork", "params" => array()) ),
            array("command" => array( SUDOPREFIX."hdiutil unmount /Volumes/VirtualBox/VirtualBox.pkg") ),
        ) ;
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
