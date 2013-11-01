<?php

Namespace Model;

class GeneratorAllLinux extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

    private $fileName;
    private $allEntries;
    private $codeInjections = array();

    public function askWhetherToCreateAutoPilot() {
        if ($this->askWhetherToDoNewAuto() != true) { return false; }
        $this->fileName = $this->askForAutopilotFileName();
        $this->askForAllModelValues();
        $this->makeAutoPilotFile();
        return true;
    }

    public function askWhetherToDoNewAuto() {
      $question = 'Create Autopilot File?';
      return self::askYesOrNo($question, true);
    }

    public function askForAutopilotFileName() {
      $question = "Enter filename. If you don't enter the full path, it'll be created here?";
      return self::askForInput($question, true);
    }

    public function askForAllModelValues() {
      $allInfoObjects = \Core\AutoLoader::getInfoObjects();
      $arrayOfClassNames = array();
      foreach ($allInfoObjects as $infoObject) {
        $modName = str_replace("Info\\", "", get_class($infoObject)) ;
        $modName = str_replace("Info", "", $modName) ;
        $arrayOfClassNames[] = $modName; }
      $chosenObject = "anything" ;
      while ($chosenObject != "") {
        $question = "Enter Module to include next. Enter none to end here and create file.";
        $chosenObject = self::askForArrayOption($question, $arrayOfClassNames);
        $infoObjectClassName = 'Info\\'.$chosenObject.'Info' ;
        if ($chosenObject=="") { return; }
        else if (class_exists($infoObjectClassName))  {
          $infoObjectToPopulate = new $infoObjectClassName();
          $this->populateEntryWithObject($infoObjectToPopulate); }
        else {
          echo "how are you even here\n";
          die(); }
      }
    }

    private function populateEntryWithObject($infoObject) {
      if (method_exists($infoObject, "autoPilotVariables")) {
        $autoVars = $infoObject->autoPilotVariables() ;
        if (is_array($autoVars) && count($autoVars) == 0) {
          echo "No Autopilot Variables Available for this Model\n";
          return; }
        foreach ($autoVars as $autoVarTitle => $autoVarSpecDetails) {
          foreach ($autoVarSpecDetails as $autoVariableName => $autoVariableDetails) {
            $question = 'Include '.$autoVariableName.' Variable?';
            $includeThis = self::askYesOrNo($question, true);
            if ($includeThis==true) {
              $this->populateEntryIntoProperty($autoVarTitle, $autoVariableName, $autoVariableDetails); } } } }
      else {
        echo "This Module does not expose an Autopilot Variables method\n"; }
      if (method_exists($infoObject, "generatorCodeInjection")) {
        $step = count ($this->allEntries) - 1 ;
        $this->codeInjections[] = $infoObject->generatorCodeInjection($step) ;}
      else {
        echo "This Module does not expose a Code Injection method\n";}
      return;
    }

    private function populateEntryIntoProperty($autoVarTitle, $autoVariableName, $autoVariableDetails) {
      $miniRay = array();
      $i=0;
      if (!is_array($autoVariableDetails)) {
        $this->allEntries[] = array($autoVarTitle => $autoVariableDetails); }
      else {
        foreach ($autoVariableDetails as $autoVariableDetailName => $autoVariableDetailType) {
          if (substr($autoVariableDetailName, strlen($autoVariableDetailName)-7, 7)=="Execute") {
            $miniRay[$autoVarTitle][$autoVariableDetailName][] = true ;}
          else {
            $question = 'Enter '.$autoVariableDetailType.' For '.$autoVariableDetailName.' Var?';
            if ($autoVariableDetailType == "boolean") {
              $miniRay[$autoVarTitle][$autoVariableDetailName][] = self::askYesOrNo($question, true); }
            else if ($autoVariableDetailType == "string") {
              $miniRay[$autoVarTitle][$autoVariableDetailName][] = self::askForInput($question); }
            else if ( is_array($autoVariableDetailType) ) {
              $keepGoing = true ;
              $question .= ' - this is an array of entries';
              while ($keepGoing == true) {
                $tinierArray = array();
                echo $question."\n";
                foreach ($autoVariableDetailType as $questionTarget) {
                  $miniQuestion = 'Enter '.$questionTarget.' ?';
                  $tinierArray[$questionTarget] = self::askForInput($miniQuestion, true); }
                $miniRay[$autoVarTitle][$autoVariableDetailName][] = $tinierArray;
                $keepGoingQuestion = 'Add Another Array Entry? (Y/N)';
                $keepGoingResult = self::askForInput($keepGoingQuestion, true);
                $keepGoing = ($keepGoingResult == "Y" || $keepGoingResult == "y") ? true : false ; } }
            else { // if the module provided us a string value
              $miniRay[$autoVarTitle][$autoVariableDetailName][] = $autoVariableDetailType; }
          }
          $i++;
          if (count($autoVariableDetails)==$i) {
            $i=0;
            $this->allEntries[] = $miniRay; } }
      }
    }

    public function askWhetherToDoExecuteVarInAuto() {
      $question = 'Execute Autopilot?';
      return self::askYesOrNo($question, true);
    }

    private function makeAutoPilotFile(){
      $stepsData = $this->getStepsDataFromArray();
      $fileData = $this->autoPilotTemplateData($stepsData);
      $this->saveAutoPilotFile($fileData);
    }

    private function getStepsDataFromArray() {
      $stepsData = "";
      foreach ($this->allEntries as $stepEntryName => $stepEntryValues) {
        $oneStep = "";
        foreach ($stepEntryValues as $stepEntryValueTitle => $stepEntryValueData) {
          $oneStep .= '          array ( "'.$stepEntryValueTitle.'" => array('."\n"; // model name
          foreach ($stepEntryValueData as $stepEntryValueDataKey => $stepEntryValueDataValue) {
            $oneStep .= '                    ';
            $isArrayValues = ( is_array($stepEntryValueDataValue[0]) ) ? true : false ;
            //$stepEntryValueDataKey is var name
            if ($isArrayValues == false) {
              if ($stepEntryValueDataValue[0] === true) {
                $oneStep .= '"'.$stepEntryValueDataKey.'" => true,'."\n"; }
              else if ($stepEntryValueDataValue[0] === false) {
                $oneStep .= '"'.$stepEntryValueDataKey.'" => false,'."\n"; }
              else {
                $oneStep .= '"'.$stepEntryValueDataKey.'" => "'.$stepEntryValueDataValue[0].'",'."\n"; } }
            else {
              $oneStep .= '            "'.$stepEntryValueDataKey.'" => array('."\n" ;
              foreach ($stepEntryValueDataValue as $arrayOfEndValues) {
                $oneStep .= '                      array(';
                $iKey = 0;
                foreach ($arrayOfEndValues as $endKey => $endValue) {
                  $oneStep .= '"'.$endKey.'" => "'.$endValue.'", ' ;
                  $iKey++; }
                $oneStep .= ' ),'."\n"; }
              $oneStep .= ' ),'."\n"; } }
          $oneStep .= '          ) , ) ,'."\n" ; }
        $stepsData .= $oneStep ; }
      return $stepsData;
    }

    private function saveAutoPilotFile($fileData) {
      $fileSavePath = (substr($this->fileName, 0, 1)) ? $this->fileName : getcwd().'/'.$this->fileName ;
      file_put_contents($fileSavePath, $fileData);
    }

    private function autoPilotTemplateData($stepsData) {

$stepsStart = <<<'TEMPLATE'
<?php

/*************************************
*      Generated Autopilot file      *
*     ---------------------------    *
*  Autopilot Generated By Cleopatra  *
*     ---------------------------    *
*************************************/

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct() {
	      $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

	    $this->steps =
	      array(

TEMPLATE
;

$stepsEnd = <<<'TEMPLATE'
	      );

	  }


TEMPLATE
;

$codeInjectionsString = "";
foreach ($this->codeInjections as $codeInjection) {
  $codeInjectionsString .= $codeInjection ; }

$templateEnd = <<<"TEMPLATE"

    $codeInjectionsString

}

TEMPLATE
;

return $stepsStart.$stepsData.$stepsEnd.$templateEnd; ;

    }

}