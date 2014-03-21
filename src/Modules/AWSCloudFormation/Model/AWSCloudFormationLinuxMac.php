<?php

Namespace Model;

class AWSCloudFormationLinuxMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "AWSCloudFormation";
        $this->installCommands = array(
            array("command"=> array(
                "cd /tmp" ,
                "git clone https://github.com/phpengine/cleopatra-aws-cloudformation aws-cloudformation",
                "mkdir -p ****PROGDIR****",
                "mv /tmp/aws-cloudformation/* ****PROGDIR****",
                "rm -rf /tmp/aws-cloudformation/" ) ),
            array("method"=> array($this, "deleteExecutorIfExists", array() )),
            array("method"=> array($this, "saveExecutorFile", array() )),
        );
        $this->uninstallCommands = array("command"=> "rm -rf ****PROGDIR****");
        $this->programDataFolder = "/opt/aws-cloudformation"; // command and app dir name
        $this->programNameMachine = "aws-cloudformation"; // command and app dir name
        $this->programNameFriendly = "AWS CloudFn!"; // 12 chars
        $this->programNameInstaller = "AWSCloudFormation";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "";
        $this->programExecutorCommand = 'java -jar ' . $this->programDataFolder . '/bin/aws-cloudformation.jar';
        $this->initialize();
    }

    public function askStatus() {
        $cmd = $this->getCFHome().DIRECTORY_SEPARATOR."bin".DIRECTORY_SEPARATOR."cfn-cmd" ;
        if (file_exists($cmd)) {
            $this->ensureCFHomeExists() ;
            $this->ensureJavaHomeExists() ;
            if ($this->executeAndGetReturnCode($cmd)==0) { return true ; } }
        return false ;
    }

    protected function getCFHome() {
        /* @todo
        if (isset($papyrus_value_for_awscf_progdir)) { }
        else { * */
        /* } * */
        return "/opt/cloudformation" ;
    }

    protected function ensureCFHomeExists() {
        if (strlen(getenv("AWS_CLOUDFORMATION_HOME")>0)) { return ; }
        else { putenv("AWS_CLOUDFORMATION_HOME={$this->getCFHome()}"); }
    }

    protected function ensureJavaHomeExists() {
        if (strlen(getenv("JAVA_HOME")>0)) { return ; }
        else {
            $fullOut = $this->executeAndLoad("ls -lah /etc/alternatives/java") ;
            $start = strpos($fullOut, ' -> ') + 4 ;
            $javaExec = substr($fullOut, $start) ;
            $javaHome = substr($javaExec, 0, strlen($javaExec)-10) ;
            putenv("JAVA_HOME=$javaHome"); }
    }
}