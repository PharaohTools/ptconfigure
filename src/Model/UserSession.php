<?php

Namespace Model;

class UserSession {

    private	$session;
    private	$ipAddress;
    private	$userBrowser;
    private	$userData;

	public function __construct(\Model\UserData $userDataModel = null) {
        $this->userData = (isset($userDataModel)) ? $userDataModel : new \Model\UserData();
        $this->session = new \Core\Session();
        $this->ipAddress = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : false ;
        $this->userBrowser =(isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : false ;
	}

    public function getUserId() {
        return $this->session->getVar('userId');
    }

    public function getLoginStatus() {
        return $this->loginCheck();
    }

	public function startUserSession() {
        if ($this->getLoginStatus()==false ) {
            $this->setUserLoginSession(); }
    }

    private function setUserLoginSession(){
        $this->session->setVar('userId', $this->userData->getIdHash() );
        $this->session->setVar('loginString',
                               hash('sha512', $this->userData->getIdHash().$this->ipAddress.$this->userBrowser));
    }

    private function loginCheck(){
        $sessUserExistEmail = $this->checkSessionUserExists();
        if ($sessUserExistEmail != false) {
             if ($this->verifySessionUser($sessUserExistEmail)){
                 return true; } }
        return false;
    }

    private function checkSessionUserExists(){
        $uid = $this->session->getVar('userId');
        $email = $this->userData->checkUserExistsByHash($uid);
        return ( strlen($email)>1 ) ? $email : false;
    }

    private function verifySessionUser(){
        $loginCheck = hash('sha512', $this->session->getVar('userId').$this->ipAddress.$this->userBrowser);
        if ($loginCheck == $this->session->getVar('loginString')) {
            return true; }
        return false;
    }

    public function logoutUserSession(){
        $this->session->reset();
        $vars = session_get_cookie_params();
        setcookie(session_name(), '', time()-42000, $vars["path"], $vars["domain"], $vars["secure"], $vars["httponly"]);
        $this->session->destroy();
    }

}