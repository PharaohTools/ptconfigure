<?php

Namespace Model;

class JavaUbuntu32 extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("32") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Java";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForJavaInstallDirectory", "params" => array()) ),
            array("command" => array(
                "git clone https://bitbucket.org/phpengine/cleopatra-oraclejava7jdk32 /tmp/oraclejdk" ,
                "cd /tmp/oraclejdk", 
                "tar -xvf jdk-1_7-32bit.tar.gz" ,
                "mkdir -p ****PROGDIR****" ,
                "cp -r /tmp/oraclejdk/jdk1.7.0_55/* ****PROGDIR****" ,
                // "rm -rf /tmp/oraclejdk" ,
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

}
