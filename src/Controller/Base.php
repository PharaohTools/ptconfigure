<?php

Namespace Controller ;

class Base {

    public $content;

    public function __construct() {
        $this->content = array(); }

    public function initUser($pageVars=array()){
        $this->content = array_merge($this->content, $pageVars);
        $this->content["userSession"] = new \Model\UserSession();
        $this->content["userSession"]->startUserSession();
        $this->content["userData"] = new \Model\UserData();
        $email = $this->content["userData"]->checkUserExistsByHash($this->content["userSession"]->getUserId());
        $this->content["userData"]->loadUser($email);
    }

    public function initForm($pageVars=array(), $formToCheck ){
        $this->content = array_merge($this->content, $pageVars);
        $this->content["formSet"] = $this->checkIfFormPosted($formToCheck);
    }

    public function checkIfFormPosted($formToCheck="") {
        return(isset($_SERVER['REQUEST_METHOD']) &&
               $_SERVER['REQUEST_METHOD'] == 'POST' &&
               isset($_REQUEST["formId"]) &&
               $_REQUEST["formId"]== $formToCheck)
               ? true : false;
    }

}