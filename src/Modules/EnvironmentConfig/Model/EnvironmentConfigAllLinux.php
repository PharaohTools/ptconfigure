<?php

Namespace Model;

// @todo This class should become two, one for Configuring the Environments section and one for config papyrus general
class EnvironmentConfigAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public $environments = array() ;
    private $environmentReplacements ;

    public function __construct($params) {
        parent::__construct($params) ;
        $this->setDefaultEnvironments();
    }

    protected function setDefaultEnvironments() {
        $this->environments[] = array(
            "any-app" => array("gen_env_name" => "default-local", "gen_env_tmp_dir" => "/tmp/"),
            "servers" => array(array("target" => "127.0.0.1", "user" =>"local", "password" => "local") ),
        ) ;
        $this->environments[] = array(
            "any-app" => array("gen_env_name" => "default-local-8080", "gen_env_tmp_dir" => "/tmp/"),
            "servers" => array(array("target" => "127.0.0.1:8080", "user" =>"local", "password" => "local") ),
        ) ;
    }

    public function askWhetherToEnvironmentConfig($arrayOfReplacements = null) {
        if ($arrayOfReplacements == null) {
            if ($this->askToScreenWhetherToEnvironmentConfig() != true) { return false; } }
        $this->setEnvironmentReplacements($arrayOfReplacements) ;
        $this->doQuestions() ;
        $this->writeEnvsToProjectFile() ;
        return true;
    }

    public function askWhetherToDeleteEnvironment() {
        if ($this->askToScreenWhetherToEnvironmentConfig("Delete") != true) { return false; }
        $this->doDelete() ;
        $this->writeEnvsToProjectFile() ;
        return true;
    }

    public function askWhetherToListEnvironments() {
        if ($this->askToScreenWhetherToEnvironmentConfig("List") != true) { return false; }
        return $this->doList() ;
    }

    public function askToScreenWhetherToEnvironmentConfig($type = "Configure") {
        $question = $type.' Environments Here?';
        $question .= ($type == "Delete") ? "\nWARNING: Deleting an environment from papyrus is final. You may be looking for boxify box-destroy instead" : "" ;
        return (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : self::askYesOrNo($question, true);
    }

    public function setEnvironmentReplacements($overrideReplacements) {
        if ($overrideReplacements == null) {
            $this->setDefaultEnvironmentReplacements(); }
        else if (is_array($overrideReplacements)) {
            $this->environmentReplacements = $overrideReplacements ; }
    }

    public function setDefaultEnvironmentReplacements() {
        $this->environmentReplacements =  $this->getDefaultEnvironmentReplacements() ;
    }

    public function getDefaultEnvironmentReplacements() {
        return array( "any-app" => array(
            array( "var" =>"gen_env_name", "friendly_text" =>"Name of this Environment"),
            array( "var" =>"gen_env_tmp_dir", "friendly_text" =>"Default Temp Dir (should usually be /tmp/)"),) );
    }

    public function doQuestions() {
        $envSuffix = array_keys($this->environmentReplacements);
        $allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
        if (count($allProjectEnvs) > 0) {
            if (isset($this->params["yes"]) && $this->params["yes"]==true) { $useProjEnvs = true ; }
            else {
                $question = 'Use existing environment settings?';
                $useProjEnvs = self::askYesOrNo($question, true); }
            if ($useProjEnvs == true ) {
                $this->environments = $allProjectEnvs;
                $i = 0;
                foreach ($this->environments as $oneEnvironmentIndex => $oneEnvironment) {
                    if (isset($this->params["environment-name"])) {
                        if ($this->params["environment-name"] != $oneEnvironment["any-app"]["gen_env_name"]) {
                            $tx = "Skipping Environment {$oneEnvironment["any-app"]["gen_env_name"]} " ;
                            $tx .= "as specified Environment is {$this->params["environment-name"]} \n" ;
                            echo $tx;
                            continue ; } }
                    $curEnvGroupRay = array_keys($this->environmentReplacements) ;
                    $curEnvGroup = $curEnvGroupRay[0] ;
                    $envName = (isset($oneEnvironment["any-app"]["gen_env_name"])) ?
                        $oneEnvironment["any-app"]["gen_env_name"] : "*unknown*" ;
                    $q  = "Do you want to modify entries applicable to any app in " ;
                    $q .= "environment $envName" ;
                    if (isset($this->params["keep-current-environments"]) && $this->params["keep-current-environments"]==true) {
                        continue ; }
                    if (isset($this->params["guess"]) && $this->params["guess"]==true) {
                        continue ; }
                    if (self::askYesOrNo($q)==true) {
                        $this->populateAnEnvironment($i, "any-app" ) ;
                        continue ; }
                    if ( isset($oneEnvironment[$curEnvGroup]) ) {
                        $q  = "Do you want to modify entries for group $curEnvGroup in " ;
                        $q .= "environment $envName" ;
                        if (self::askYesOrNo($q)==true) {
                            $this->populateAnEnvironment($i, $curEnvGroup) ; } }
                    else {
                        echo "Settings for ".$curEnvGroup." not setup for environment " .
                            "{$oneEnvironment["any-app"]["gen_env_name"]} enter them manually.\n";
                        $ix = ($oneEnvironmentIndex != null) ? $oneEnvironmentIndex : $i ;
                        $this->populateAnEnvironment($ix, $curEnvGroup) ; }
                    $i++; } } }
        $i = 0;
        $more_envs = true;
        while ($more_envs == true) {
            if (count($this->environments)==0) {
                $this->populateAnEnvironment($i, $envSuffix[0]);
                if (isset($this->params["add-single-environment"])) {
                    unset($this->params["add-single-environment"]) ; }
                $more_envs = false ; }
            else {
                if (isset($this->params["guess"]) && (!isset($this->params["add-single-environment"])) ) {
                    $more_envs = false; }
                else {
                    $question = 'Do you want to add another environment?';
                    if (isset($this->params["add-single-environment"])) {
                        $add_another_env = true ; }
                    else {
                        $add_another_env = self::askYesOrNo($question); }
                    if ($add_another_env == true) {
                        $i = count($this->environments) ;
                        $this->populateAnEnvironment($i, $envSuffix[0]);
                        if (isset($this->params["add-single-environment"])) {
                            unset($this->params["add-single-environment"]) ; }
                        $more_envs = false ; }
                    else {
                        $more_envs = false; } } }
            $i++; }
    }

    public function doDelete() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
        if (is_array($allProjectEnvs) && count($allProjectEnvs) > 0) {
            $this->environments = $allProjectEnvs ;
            $keys = array_keys($this->environments) ;
            for ($i = 0 ; $i<count($this->environments) ; $i++) {
                $envName = $this->environments[$keys[$i]]["any-app"]["gen_env_name"] ;
                if ($envName == $this->params["environment-name"]) {
                    $q = "Environment $envName found. Are you sure you want to delete it?" ;
                    $res = (isset($this->params["yes"])) ? true : self::askYesOrNo($q) ;
                    if ($res==true) {
                        $logging->log("Removing environment $envName.") ;
                        unset ($this->environments[$keys[$i]]) ;
                        $this->environments = array_merge($this->environments);
                        continue ; } } } }
        else {
            $logging->log("No environments exist here. Nothing to delete.") ; }
    }

    public function doList() {
        $allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
        return $allProjectEnvs ;
    }

    private function populateAnEnvironment($i, $appEnvType) {
        $envName = (isset($this->environments[$i]["any-app"]["gen_env_name"])) ?
            $this->environments[$i]["any-app"]["gen_env_name"] : null ;
        echo "Environment ".($i+1)." $envName : \n";

        if (!isset($this->environments[$i]["any-app"]) || $appEnvType =="any-app") {
            echo "Default Settings for Any App not setup for environment $envName enter them now.\n";
            $defaultReplacements = $this->getDefaultEnvironmentReplacements() ;
            foreach ($defaultReplacements["any-app"] as $replacementQuestion) {
                if ($replacementQuestion["var"] == "gen_env_name" && isset($this->params["environment-name"])) {
                    $this->environments[$i]["any-app"][$replacementQuestion["var"]] = $this->params["environment-name"] ; }
                else if ($replacementQuestion["var"] == "gen_env_tmp_dir" && isset($this->params["tmp-dir"])) {
                    $this->environments[$i]["any-app"][$replacementQuestion["var"]] = $this->params["tmp-dir"] ; }
                else {
                    $this->environments[$i]["any-app"][$replacementQuestion["var"]] = self::askForInput("Value for: ".$replacementQuestion["friendly_text"]); } } }

        if ($appEnvType=="any-app") { $this->environments[$i]["servers"] = $this->getServers(); }
        else {
            foreach ($this->environmentReplacements[$appEnvType] as $replacementQuestion) {
                $this->environments[$i][$appEnvType][$replacementQuestion["var"]]
                    = self::askForInput("Value for: ".$replacementQuestion["friendly_text"]); } }
    }

    public function getServers() {
        $servers = array();
        $serverAttributes = array("target", "user", "password");
        $keepGoing = (isset($this->params["no-manual-servers"])) ? false : true ;
        $question = 'Enter Servers - this is an array of entries';
        while ($keepGoing == true) {
            $tinierArray = array();
            echo $question."\n";
            foreach ($serverAttributes as $questionTarget) {
                $miniQuestion = 'Enter '.$questionTarget.' ?';
                $tinierArray[$questionTarget] = self::askForInput($miniQuestion, true); }
            $servers[] = $tinierArray;
            $keepGoingQuestion = 'Add Another Server? (Y/N)';
            $keepGoingResult = self::askForInput($keepGoingQuestion, true);
            $keepGoing = ($keepGoingResult == "Y" || $keepGoingResult == "y") ? true : false ; }
        return $servers;
    }

    private function writeEnvsToProjectFile() {
        // @todo redo this so it works eh
//        $all_envs = $this->removeDefaultEnvironments() ;
//        \Model\AppConfig::setProjectVariable("environments", $all_envs);
        \Model\AppConfig::setProjectVariable("environments", $this->environments);
    }

    // @todo this setup of having a default-local environment is not complete
    private function removeDefaultEnvironments() {
        $all_envs = array() ;
        // unset($this->environments[0]) ;
        foreach ($this->environments as $environment) {
            $all_envs[] = $environment ; }
        return $all_envs ;
    }

}