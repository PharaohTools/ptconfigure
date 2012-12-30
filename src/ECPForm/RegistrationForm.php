<?php

Namespace ECPForm;

class RegistrationForm extends BaseForm {

    /* @todo refactor this so that it becomes one with the set post bind validators */
    public function setFormFields() {
        parent::setFormFields();
        $this->formFields[] = array( "fieldName"=>"userName", "type"=>"text", "validators"=>array() );
        $this->formFields[] = array( "fieldName"=>"email", "type"=>"text", "validators"=>array() );
        $this->formFields[] = array( "fieldName"=>"userPass", "type"=>"password", "validators"=>array() );
        $this->formFields[] = array( "fieldName"=>"userPass2", "type"=>"password", "validators"=>array() );
    }

    public function setPostBindValidators() {
        parent::setPostBindValidators();
        $this->bindValueOrNullFieldsToForm();
        $this->addValidators();
    }

    public function formSuccessCallBack(){
        $userModel = new \Model\UserData();
        $userWasSet = ($userModel->setNewUser($this->formRequest["userName"],
                                              $this->formRequest["email"],
                                              $this->formRequest["userPass"]) )
                      ? true : false ;
        return $userWasSet;
    }

    private function addValidators() {
        $this->addValidatorForUserName();
        $this->addValidatorForEMail();
        $this->addValidatorForUserPass();
        $this->addValidatorForUserPass2();
    }

    private function bindValueOrNullFieldsToForm() {
        $this->formRequest["userName"]= (isset($this->formRequest["userName"]))? $this->formRequest["userName"]:null;
        $this->formRequest["email"]= (isset($this->formRequest["email"]))? $this->formRequest["email"]:null;
        $this->formRequest["userPass"]= (isset($this->formRequest["userPass"]))? $this->formRequest["userPass"]:null;
        $this->formRequest["userPass2"]= (isset($this->formRequest["userPass2"]))? $this->formRequest["userPass2"]:null;
    }

    private function addValidatorForUserName() {
        $this->postBindValidators[] = array(
            "fieldName"=>"userName",
            "validators"=>array( "notBlank"=>array( "fieldValue"=>$this->formRequest["userName"] ) ) );
        return $this;
    }

    private function addValidatorForEMail() {
        $this->postBindValidators[] = array(
            "fieldName"=>"email",
            "validators"=>array( "notBlank"=>array("fieldValue"=>$this->formRequest["email"]),
                                 "isEmailAddress"=>array("fieldValue"=>$this->formRequest["email"]),
                                 "isUniqueEmail"=>array("fieldValue"=>$this->formRequest["email"]) ) );
        return $this;
    }

    private function addValidatorForUserPass() {
        $this->postBindValidators[] = array(
            "fieldName"=>"userPass",
            "validators"=>array( "notBlank"=>array("fieldValue"=>$this->formRequest["userPass"]),
                                 "moreThan6Chars"=>array("fieldValue"=>$this->formRequest["userPass"]),
                                 "lessThan10Chars"=>array("fieldValue"=>$this->formRequest["userPass"]) ) );
        return $this;
    }

    private function addValidatorForUserPass2() {
        $this->postBindValidators[] = array(
            "fieldName"=>"userPass2",
            "validators"=>array( "notBlank"=>array("fieldValue"=>$this->formRequest["userPass2"]),
                                 "moreThan6Chars"=>array("fieldValue"=>$this->formRequest["userPass2"]),
                                 "lessThan10Chars"=>array("fieldValue"=>$this->formRequest["userPass2"]),
                                 "mustMatch"=>array( "fieldValue"=>$this->formRequest["userPass2"],
                                                     "targetValue"=>$this->formRequest["userPass"],
                                                     "targetFieldName"=>"User Password" ) ) );
        return $this;
    }

}