<?php

Namespace ECPForm;

class BaseForm {

    public $dbo;
    public $dbHelpers;
    public $validationHelpers;
    public $formRequest;
    public $validationResult;
    public $formFields;
    public $postBindValidators;

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
        $postBindValResult = $this->executeValidationFunctions("post") ;
        if ($this->validationResult["results"]=="true" && $postBindValResult["results"]=="true") {
            $this->validationResult["messages"] =
                array_merge($this->validationResult["messages"], $postBindValResult["messages"]) ;
            $this->validationResult["errors"] =
                array_merge($this->validationResult["errors"], $postBindValResult["errors"]) ;
            $this->doSuccessCallback($this->validationResult);
        } else {
            $this->validationResult["results"] = false;
            $this->validationResult["errors"] =
                array_merge($this->validationResult["errors"], $postBindValResult["errors"]) ;
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
            foreach ($fieldValidators as $fieldValFunc => $fieldValOpts) {
                $singleValResult = $this->validationHelpers->$fieldValFunc( $fieldValOpts);
                if (array_key_exists("false", $singleValResult)) {
                    $validationResults["results"] = "false";
                    $singleError = array("field" => $fieldName, "messages" => $singleValResult["false"]);
                    $validationResults["errors"][] = $singleError; } } }
        return $validationResults;
    }

    public function setFormFields() {
        $this->formFields = array();
    }

    public function setPostBindValidators() {
        $this->postBindValidators = array();
    }

}
