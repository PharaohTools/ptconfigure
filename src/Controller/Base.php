<?php

Namespace Controller ;

class Base {

    private $messages;
    public $content;

    public function __construct() {
        $this->content = array();
        $this->getMessages();
        $this->content = $this->getContentArray();
    }

    public function getMessages() {
        $this->messages[] = ( isset($_REQUEST["msg"]) ) ? $_REQUEST["msg"] : null ;
    }

    public function getContentArray() {
        $this->content["messages"] = $this->getMessages();
        return $this->content;
    }

    public function initUser($pageVars){
        $this->content = array_merge($this->content, $pageVars);
        $this->content["user"] = new \Model\User();
    }

    public function checkIfFormPosted($formToCheck) {
        return ($_SERVER['REQUEST_METHOD'] == 'POST'
            && isset($_REQUEST["formId"])
            && $_REQUEST["formId"]== $formToCheck)
            ?  true : false;
    }

}