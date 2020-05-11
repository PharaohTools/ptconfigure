<?php

Namespace Model;

class HAProxyConfigureLinuxMac extends BaseTemplater {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Configure") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "HAProxy";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "setDefaultReplacements", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setOverrideReplacements", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setTemplateFile", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setTemplate", "params" => array()) ),
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/HAProxy"; // command and app dir name
        $this->programNameMachine = "haproxy"; // command and app dir name
        $this->programNameFriendly = "HA Proxy Server!"; // 12 chars
        $this->programNameInstaller = "HA Proxy Server";
        $this->targetLocation = DS."etc".DS."haproxy".DS."haproxy.cfg" ;
        $this->initialize();
    }

    protected function setDefaultReplacements() {
        // set array with default values
        $this->replacements = array(
            // @todo the suffix string here is to denote that you should include the whole line (or multiple lines) in your override answer
            "global_log" => "127.0.0.1 local0 notice",
            "global_maxconn" => "20000",
            "global_user" => "haproxy",
            "global_group" => "haproxy",
            "defaults_log" => "global",
            "defaults_mode" => "http",
            "defaults_option_string" => "option dontlognull\n    option redispatch", # option httplog\n
            "defaults_retries" => "3",
            "defaults_timeout_connect" => "5000",
            "defaults_timeout_client" => "10000",
            "defaults_timeout_server" => "10000",
            "listen_appname" => "appname",
            "listen_ip_port" => "0.0.0.0:80",
            "listen_mode" => "http",
            "listen_balance" => "roundrobin",
            "listen_option_string" => "option httpclose\n    option forwardfor",
            "listen_server_string" => $this->getServerString()
        ) ;

        $stats_defaults = array(
            "listen_stats_enable" => "enable",
            "listen_stats_uri_string" => "stats uri /haproxy?stats",
            "listen_stats_realm_string" => 'stats realm Strictly\ Private',
            "listen_stats_auth_string" => "stats auth ptconfigure:ptconfigure", # use whole line so we can include multiple
            "listen_stats_appname" => "stats",
            "listen_stats_ip_port" => "0.0.0.0:1936"
        );

        if (isset($this->params["with-stats"])) {
            $this->replacements = array_merge($this->replacements, $stats_defaults) ;
        }
    }

    protected function getServerString() {
        $servers = $this->getServersArray() ;
        $st = "" ;
        $loggingFactory = new \Model\Logging() ;
        if (isset($this->params["private-targets"]) && $this->params["private-targets"]==true) {
            $log = $loggingFactory->getModel($this->params) ;
            $log->log("Attempting to use private targets for Load Balancing", $this->getModuleName()) ;
            $target_scope = "target_private" ; }
        else {
            $target_scope = "target" ; }
        foreach ($servers as $server) {
            $st .= "server {$server["name"]} {$server[$target_scope]}:{$this->getTemplatePort()} check\n" ; }
        return $st ;
    }

    protected function getTemplatePort() {
        if (isset($this->params["template_target_port"])) { return $this->params["template_target_port"] ; }
        $colonPos = strpos($this->replacements["listen_ip_port"], ":");
        return substr($this->replacements["listen_ip_port"], $colonPos) ;
    }

    protected function setTemplateFile() {
        $this->templateFile = str_replace("Model", "Templates", dirname(__FILE__) ) ;
        if (isset($this->params["with-stats"])) {
            $this->templateFile .= DS."haproxy-with-stats.cfg" ; }
        else {
            $this->templateFile .= DS."haproxy.cfg" ; }
    }

    protected function getServersArray() {
        if (isset($this->params["env"])) {
            $this->params["environment-name"] = $this->params["env"] ; }
        if (!isset($this->params["environment-name"])) {
            $loggingFactory = new \Model\Logging() ;
            $log = $loggingFactory->getModel($this->params) ;
            $log->log("No environment name provided for Load Balancing", $this->getModuleName()) ;
            $this->params["environment-name"] = $this->askForEnvironment() ; }
        $envs = $this->getEnvironments();
        $names = $this->getEnvironmentNames($envs) ;
        $servers = $envs[$names[$this->params["environment-name"]]]["servers"];
        return $servers ;
    }

    private function askForEnvironment(){
        $question = 'What is the environment name you want to balance load to? ';
        $input = self::askForInput($question, true);
        return $input ;
    }

    protected function getEnvironmentNames($envs) {
        $eNames = array() ;
        foreach ($envs as $envKey => $env) {
            $envName = $env["any-app"]["gen_env_name"] ;
            $eNames[$envName] = $envKey ; }
        return $eNames ;
    }

    protected function getEnvironments() {
        return \Model\AppConfig::getProjectVariable("environments");
    }

}