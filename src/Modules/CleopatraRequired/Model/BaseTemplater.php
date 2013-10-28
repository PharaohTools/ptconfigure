<?php

Namespace Model;

class BaseTemplater extends BaseLinuxApp {

    protected $replacements ;
    protected $templateFile ;
    protected $targetLocation ;

    protected function setOverrideReplacements() {
        if (isset($this->params["no-overrides"]) && $this->params["no-overrides"]!=true || !isset($this->params["no-overrides"]) ) {
            $newArray = array();
            foreach ($this->replacements as $replacementKey => $replacementValue) {
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
        $templateObject->template($templateData, $this->replacements, $this->targetLocation) ;
    }

}