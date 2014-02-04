<?php

Namespace Model;

class BaseTemplater extends BaseTestInit {

    protected $replacements ;
    protected $templateFile ;
    protected $targetLocation ;

    protected function setOverrideReplacements() {
        if (isset($this->params["no-overrides"]) && $this->params["no-overrides"]!=true || !isset($this->params["no-overrides"]) ) {
            $newArray = array();
            foreach ($this->replacements as $replacementKey => $replacementValue) {
                if (isset($this->params["template_{$replacementKey}"])) {
                    $newArray[$replacementKey] = $this->params["template_{$replacementKey}"] ;
                    continue ; }
                $doChange = $this->askYesOrNo("Set non-default value for $replacementKey? Default is $replacementValue");
                if ($doChange) {
                    $newArray[$replacementKey] = $this->askForInput("What value for $replacementKey?"); }
                else {
                    $newArray[$replacementKey] = $replacementValue ; } }
            $this->replacements = $newArray ; }
    }

    protected function setTemplate() {
        $templateObject = \Model\Templating::getModel($this->params) ;
        $templateData = file_get_contents($this->templateFile) ;
        if (isset($this->params["target-location"])) { $this->targetLocation = $this->params["target-location"] ; }
        $templateObject->template($templateData, $this->replacements, $this->targetLocation) ;
    }

}