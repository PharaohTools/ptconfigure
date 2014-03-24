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
        array("command" => array(
            "git clone https://bitbucket.org/phpengine/cleopatra-oraclejava7jdk /tmp/oraclejdk" ,
            "mkdir -p ****PROGDIR****" ,
            "cp -r /tmp/oraclejdk/* ****PROGDIR****" ,
            "rm -rf /tmp/oraclejdk" ,
            "cd ****PROGDIR****",
            "chmod a+x ****PROGDIR****",
            'echo \'JAVA_HOME=****PROGDIR****\' >> /etc/profile',
            'echo \'PATH=$PATH:$HOME/bin:$JAVA_HOME/bin\' >> /etc/profile',
            'echo \'export JAVA_HOME\' >> /etc/profile',
            'echo \'export PATH\' >> /etc/profile',
            'sudo update-alternatives --install "/usr/bin/java" "java" "****PROGDIR****/bin/java" 1 ',
            'sudo update-alternatives --install "/usr/bin/javac" "javac" "****PROGDIR****/bin/javac" 1 ',
            'sudo update-alternatives --install "/usr/bin/javaws" "javaws" "****PROGDIR****/bin/javaws" 1 ',
            'sudo update-alternatives --set java ****PROGDIR****/bin/java ',
            'sudo update-alternatives --set javac ****PROGDIR****/bin/javac ',
            'sudo update-alternatives --set javaws ****PROGDIR****/bin/javaws ',
            '. /etc/profile' ) )
        );
    //@todo uninstall commands of java
    $this->uninstallCommands = array();
    $this->programDataFolder = "/var/lib/jvm/jdk1.7";
    $this->programNameMachine = "java"; // command and app dir name
    $this->programNameFriendly = "!!Java JDK!!"; // 12 chars
    $this->programNameInstaller = "The Oracle Java JDK 1.7";
    $this->registeredPreInstallFunctions = array("askForJavaInstallDirectory");
    $this->registeredPreUnInstallFunctions = array("askForJavaInstallDirectory");
    $this->initialize();
  }

  protected function askForJavaInstallDirectory($autoPilot=null){
    if (isset($autoPilot) &&
      $autoPilot->{"JavaInstallDirectory"} ) {
      $this->programDataFolder = $autoPilot->{"JavaInstallDirectory"}; }
    else {
      if (isset($this->params["yes"]) && $this->params["yes"]==true) {
        return; }
      $question = "Enter Java Install Directory (no trailing slash):";
      $this->programDataFolder = self::askForInput($question, true); }
  }

}