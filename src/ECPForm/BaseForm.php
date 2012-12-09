<?php

Namespace ECPForm;

class BaseForm {

    public $dbo;
    public $dbHelpers;
    public $validationHelpers;
    public $formRequest;
    public $validationResult;
    public $formFields;

	public function __construct() {
        $this->dbo = new \Core\Database();
        $this->dbHelpers = new \Core\DatabaseHelpers();
        $this->validationHelpers = new \Core\ValidationHelpers();
        $this->validationResult=array();
        $this->bindForm();
        $this->postBindForm();
	}

    public function bindForm() {
        $this->setFormFields();
        $this->formRequest = $this->dbHelpers->sanitize($_REQUEST);
        $this->validationResult = $this->validateForm();
    }

    public function postBindForm() {
        $this->setPostBindValidators() ;
        $postBindValidationResult = $this->executeValidationFunctions("post") ;
        if ($this->validationResult["results"]=="true" && $postBindValidationResult["results"]=="true") {
            $this->validationResult["messages"] =
                array_merge($this->validationResult["messages"], $postBindValidationResult["messages"]) ;
            $this->validationResult["errors"] =
                array_merge($this->validationResult["errors"], $postBindValidationResult["errors"]) ;
            $this->doSuccessCallback($this->validationResult);
        } else {
            $this->validationResult["results"] = false;
            $this->validationResult["errors"] =
                array_merge($this->validationResult["errors"], $postBindValidationResult["errors"]) ;
            $this->doFailureCallback($this->validationResult);
        }
    }

    public function getValidationResult() {
        return $this->validationResult;
    }

    public function validateForm() {
        $validationResults = $this->executeValidationFunctions("pre");
        return $validationResults;
    }

    private function doSuccessCallback($validationResults){
        if (method_exists($this, "formSuccessCallBack")) {$this->formSuccessCallBack($validationResults);}
    }

    private function doFailureCallback($validationResults){
        if (method_exists($this, "formFailureCallBack")) {$this->formFailureCallBack($validationResults);}
    }

    private function executeValidationFunctions($preOrPostBind) {
        $validationResults = array("results" => "true", "errors" => array(), "messages" => array() );
        $validatorSource = ($preOrPostBind == "pre") ? $this->formFields : $this->postBindValidators ;
        foreach ($validatorSource as $formFieldDetails) {
            $fieldName = $formFieldDetails["fieldName"];
            $fieldValidators = $formFieldDetails["validators"];
            foreach ($fieldValidators as $fieldValidatorFunction => $fieldValidatorOptions) {
                $singleValidationResult = $this->validationHelpers->$fieldValidatorFunction($fieldName, $fieldValidatorOptions);
                if (array_key_exists("false", $singleValidationResult)) {
                    $validationResults["results"] = "false";
                    $singleError = array("field" => $fieldName, "messages" => $singleValidationResult["false"]);
                    $validationResults["errors"][] = $singleError;
                }
            }
        }

        return $validationResults;
    }

}