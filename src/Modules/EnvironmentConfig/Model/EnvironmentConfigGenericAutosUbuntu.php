<?php

Namespace Model;

class EnvironmentConfigGenericAutosUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("GenericAutos") ;

    protected $defaultEnvironments;
    protected $chosenEnvironment;

    protected $actionsToMethods =
        array(
            "config-default" => "performGenericEnvironmentConfig",
            "configure-default" => "performGenericEnvironmentConfig",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "EnvConfig";
        $this->programNameMachine = "env-config"; // command and app dir name
        $this->programNameFriendly = "ConfigEnvs!"; // 12 chars
        $this->programNameInstaller = "Default Configure your Environments";
        $this->initialize();
    }

    public function performGenericEnvironmentConfig() {
        $this->setDefaultEnvironments();
        $this->chooseDefaultEnvironment();
        return $this->setEnvConfigParamsAndInstall() ;
    }

    protected function setDefaultEnvironments() {
        $this->defaultEnvironments["local"] = array(
            "any-app" => array("gen_env_name" => "default-local", "gen_env_tmp_dir" => "/tmp/"),
            "servers" => array(array("target" => "127.0.0.1", "user" => "any", "password" => "any") ),
        ) ;
        $this->defaultEnvironments["local-80"] = array(
            "any-app" => array("gen_env_name" => "default-local-80", "gen_env_tmp_dir" => "/tmp/"),
            "servers" => array(array("target" => "127.0.0.1:80", "user" => "any", "password" => "any") ),
        ) ;
        $this->defaultEnvironments["local-8080"] = array(
            "any-app" => array("gen_env_name" => "default-local-8080", "gen_env_tmp_dir" => "/tmp/"),
            "servers" => array(array("target" => "127.0.0.1:8080", "user" => "any", "password" => "any") ),
        ) ;
        $this->defaultEnvironments["phlagrant-host"] = array(
            "any-app" => array("gen_env_name" => "phlagrant-host", "gen_env_tmp_dir" => "/tmp/"),
            "servers" => array(array("target" => "127.0.0.1", "user" => "phlagrant", "password" => "phlagrant") ),
        ) ;
        $this->defaultEnvironments["phlagrant-box"] = array(
            "any-app" => array("gen_env_name" => "phlagrant-box", "gen_env_tmp_dir" => "/tmp/"),
            "servers" => array(array("target" => "127.0.0.1", "user" => "phlagrant", "password" => "phlagrant") ),
        ) ;
    }

    public function chooseDefaultEnvironment() {
        $options = array_keys($this->defaultEnvironments);
        if (isset($this->params["default-environment-name"]) &&
            in_array($this->params["default-environment-name"], $options) ) {
            $this->chosenEnvironment = $this->params["default-environment-name"] ;
            return; }
        $question = "Pick a default environment name to install:" ;
        $this->chosenEnvironment = self::askForArrayOption($question, $options, true);
    }

    public function setEnvConfigParamsAndInstall() {
        $envConfNewParams = array(
            "environment-name" => $this->defaultEnvironments[$this->chosenEnvironment]["any-app"]["gen_env_name"] ,
            "tmp-dir" => $this->defaultEnvironments[$this->chosenEnvironment]["any-app"]["gen_env_tmp_dir"],
            "keep-current-environments" => true,
            "add-single-environment" => true,
            "servers" => $this->defaultEnvironments[$this->chosenEnvironment]["servers"],
        );
        $envConfAllParams = array_merge($envConfNewParams, $this->params);
        $envConfigFactory = new \Model\EnvironmentConfig();
        $envConfig = $envConfigFactory->getModel($envConfAllParams);
        return $envConfig->askWhetherToEnvironmentConfig() ;
    }

}