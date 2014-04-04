<?php

Namespace Model;

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

    public function askWhetherToEnvironmentConfig($arrayOfReplacements = null) {
        if ($arrayOfReplacements == null) {
            if ($this->askToScreenWhetherToEnvironmentConfig() != true) { return false; } }
        $this->setEnvironmentReplacements($arrayOfReplacements) ;
        $this->doQuestions() ;
        $this->writeEnvsToProjectFile() ;
        return true;
    }

    public function askToScreenWhetherToEnvironmentConfig() {
      $question = 'Configure Environments Here?';
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
          foreach ($this->environments as $oneEnvironment) {
              $curEnvGroupRay = array_keys($this->environmentReplacements) ;
              $curEnvGroup = $curEnvGroupRay[0] ;
              $envName = (isset($oneEnvironment["any-app"]["gen_env_name"])) ?
                  $oneEnvironment["any-app"]["gen_env_name"] : "*unknown*" ;
              $q  = "Do you want to modify entries applicable to any app in " ;
              $q .= "environment $envName" ;
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
                  $this->populateAnEnvironment($i, $curEnvGroup) ; }
              $i++; } } }
        $i = 0;
        $more_envs = true;
        while ($more_envs == true) {
            if (count($this->environments)==0) {
                $this->populateAnEnvironment($i, $envSuffix[0]);}
            else {
                $question = 'Do you want to add another environment?';
                $add_another_env = self::askYesOrNo($question);
                if ($add_another_env == true) {
                    $i = count($this->environments) + 1 ;
                    $this->populateAnEnvironment($i, $envSuffix[0]); }
                else {
                    $more_envs = false; } }
            $i++; }
    }

    private function populateAnEnvironment($i, $appEnvType) {
      $envName = (isset($this->environments[$i]["any-app"]["gen_env_name"])) ?
          $this->environments[$i]["any-app"]["gen_env_name"] : null ;
      echo "Environment ".($i+1)." $envName : \n";

      if (!isset($this->environments[$i]["any-app"]) || $appEnvType =="any-app") {
        echo "Default Settings for Any App not setup for environment $envName enter them now.\n";
          $defaultReplacements = $this->getDefaultEnvironmentReplacements() ;
          foreach ($defaultReplacements["any-app"] as $replacementQuestion) {
              $this->environments[$i]["any-app"][$replacementQuestion["var"]]
                  = self::askForInput("Value for: ".$replacementQuestion["friendly_text"]); }
          if ($appEnvType=="any-app") { $this->environments[$i]["servers"] = $this->getServers(); } }
      else {
          foreach ($this->environmentReplacements[$appEnvType] as $replacementQuestion) {
              $this->environments[$i][$appEnvType][$replacementQuestion["var"]]
                  = self::askForInput("Value for: ".$replacementQuestion["friendly_text"]); } }
    }

    public function getServers() {
      $servers = array();
      $serverAttributes = array("target", "user", "password");
      $keepGoing = true ;
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
        \Model\AppConfig::setProjectVariable("environments", $this->environments);
    }

}