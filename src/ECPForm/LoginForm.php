<?php

Namespace ECPForm;

class LoginForm extends BaseForm  {

    public function setFormFields() {
        $this->formFields = array();
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
    }

    public function setPostBindValidators() {
        $this->postBindValidators[] = array(
            "fieldName"=>"email",
            "validators"=>array(
                "notBlank"=>array("fieldValue"=>$this->formRequest["email"]),
                "isEmailAddress"=>array("fieldValue"=>$this->formRequest["email"]),
            )
        );
        $this->postBindValidators[] = array(
            "fieldName"=>"userPass",
            "validators"=>array(
                "notBlank"=>array("fieldValue"=>$this->formRequest["userPass"]),
                "moreThan6Chars"=>array("fieldValue"=>$this->formRequest["userPass"]),
                "lessThan10Chars"=>array("fieldValue"=>$this->formRequest["userPass"]),
                "validUserLogin"=>array("email"=>$this->formRequest["email"],
                                        "userPass"=>$this->formRequest["userPass"])
            )
        );
    }

    public function formSuccessCallBack(){
    }

    public function formFailCallBack(){

    }

}