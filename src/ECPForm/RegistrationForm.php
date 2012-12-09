<?php

Namespace ECPForm;

class RegistrationForm extends BaseForm {

    public function setFormFields() {
        $this->formFields = array();
        $this->formFields[] = array(
            "fieldName"=>"userName",
            "type"=>"text",
            "validators"=>array()
        );
        $this->formFields[] = array(
            "fieldName"=>"email",
            "type"=>"text",
            "validators"=>array()
        );
        $this->formFields[] = array(
            "fieldName"=>"userPass",
            "type"=>"password",
            "validators"=>array()
        );
        $this->formFields[] = array(
            "fieldName"=>"userPass2",
            "type"=>"password",
            "validators"=>array()
        );
    }

    public function setPostBindValidators() {
        $this->postBindValidators = array();
        $this->postBindValidators[] = array(
            "fieldName"=>"userName",
            "validators"=>array(
                "notBlank"=>array(
                    "fieldValue"=>$this->formRequest["userName"]
                )
            )
        );
        $this->postBindValidators[] = array(
            "fieldName"=>"email",
            "validators"=>array(
                "notBlank"=>array("fieldValue"=>$this->formRequest["email"]),
                "isEmailAddress"=>array("fieldValue"=>$this->formRequest["email"]),
                "isUniqueEmail"=>array("fieldValue"=>$this->formRequest["email"])
            )
        );
        $this->postBindValidators[] = array(
            "fieldName"=>"userPass",
            "validators"=>array(
                "notBlank"=>array("fieldValue"=>$this->formRequest["userPass"]),
                "moreThan6Chars"=>array("fieldValue"=>$this->formRequest["userPass"]),
                "lessThan10Chars"=>array("fieldValue"=>$this->formRequest["userPass"])
            )
        );
        $this->postBindValidators[] = array(
            "fieldName"=>"userPass2",
            "validators"=>array(
                "notBlank"=>array("fieldValue"=>$this->formRequest["userPass2"]),
                "moreThan6Chars"=>array("fieldValue"=>$this->formRequest["userPass2"]),
                "lessThan10Chars"=>array("fieldValue"=>$this->formRequest["userPass2"]),
                "mustMatch"=>array(
                    "fieldValue"=>$this->formRequest["userPass2"],
                    "targetValue"=>$this->formRequest["userPass"],
                    "targetFieldName"=>"User Password"
                )
            )
        );
    }

    public function formSuccessCallBack(){
        $userModel = new \Model\User();
        $userModel->setNewUser($this->formRequest["userName"], $this->formRequest["email"], $this->formRequest["userPass"]);
    }

    public function formFailureCallBack(){
    }

}