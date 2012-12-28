<?php

Namespace ECPForm;

class LoginForm extends BaseForm  {

    /* @todo refactor this so that it becomes one with the set post bind validators */
    public function setFormFields() {
        parent::setFormFields();
        $this->formFields[] = array(
            "fieldName"=>"email",
            "type"=>"text",
            "validators"=>array() );
        $this->formFields[] = array(
            "fieldName"=>"userPass",
            "type"=>"password",
            "validators"=>array() );
    }

    public function setPostBindValidators() {
        parent::setPostBindValidators();
        $formRequestEmail = ( isset($this->formRequest["email"]) ) ? $this->formRequest["email"] : null  ;
        $formRequestPass = ( isset($this->formRequest["userPass"]) ) ? $this->formRequest["userPass"] : null  ;
        $this->postBindValidators[] = array(
            "fieldName"=>"email",
            "validators"=>array(
                "notBlank"=>array("fieldValue"=> $formRequestEmail)  ,
                "isEmailAddress"=>array("fieldValue"=>$formRequestEmail) ) );
        $this->postBindValidators[] = array(
            "fieldName"=>"userPass",
            "validators"=>array(
                "notBlank"=>array("fieldValue"=>$formRequestPass),
                "moreThan6Chars"=>array("fieldValue"=>$formRequestPass),
                "lessThan10Chars"=>array("fieldValue"=>$formRequestPass),
                "validUserLogin"=>array("email"=>$formRequestEmail,
                                        "userPass"=>$formRequestPass) ) );
    }

    public function formSuccessCallBack(){
        $userData = new \Model\UserData();
        $formRequestEmail = ( isset($this->formRequest["email"]) ) ? $this->formRequest["email"] : null  ;
        $userData->loadUser($formRequestEmail);
        $userSession = new \Model\UserSession($userData);
        $userSession->startUserSession();
        return is_object($userSession);
    }

}