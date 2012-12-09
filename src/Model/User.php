<?php

Namespace Model;

class User {

    private	$dbo;
    private	$session;
    private	$id;
    private $idHash;
    private $timestamp_login;
    private $timestamp_creation;
    private	$userName;
    private	$email;
    private	$password;
    private	$amILoggedIn;
    private	$ip_address;
    private	$user_browser;

	public function __construct() {
        $this->dbo = new \Core\Database();
        $this->session = new \Core\Session();
        $this->ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user.
        $this->user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
        $this->amILoggedIn = $this->loginCheck();
	}

    public function getLoginStatus() {
        return $this->amILoggedIn;
    }

	private function loadUser($email) {
        $stmt = $this->dbo->getDbo()->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email); // Bind "$email" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();
        $stmt->bind_result($this->id, $this->idHash,  $this->timestamp_creation, $this->userName, $this->email, $this->password); // get variables from result.
        $stmt->fetch();
	}

    public function setNewUser($userName, $email, $password) {
        $this->userName = $userName;
        $this->timestamp_creation = time();
        $this->email = $email;
        $this->password = md5($password);
        $this->save();
    }

    private function save() {
        $query = 'INSERT INTO users (`hash`, `timeStamp`, `userName`, `email`, `password`) VALUES ( ';
        $query .= '"'.$this->hash.'", "'.$this->timestamp_creation.'", "'.$this->userName.'", "'.$this->email.'", "'.$this->password.'"); ';
        $this->dbo->doQuery($query) ;
    }

    private function createHash(){
        //function from http://stackoverflow.com/questions/853813/how-to-create-a-random-string-using-php
        $random_string = "";
        $valid_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $num_valid_chars = strlen($valid_chars);
        for ($i = 0; $i < 32; $i++) {
            $random_pick = mt_rand(1, $num_valid_chars);
            $random_char = $valid_chars[$random_pick-1];
            $random_string .= $random_char;
        }
        return $random_string;
    }


    public function attemptLogin($email, $password) {
        $this->password = md5($password);
        if ($this->checkUserExists($email) &&
            $this->checkPasswordCorrect($email, $this->password) ) {
            $this->loadUser($email);
            $this->setUserLoginSession();
            /*
            $this->recordLoginTimeStamp();
            */
            return true;
         }
        return false;
    }

    private function checkIfSessionVariablesAreSet() {
        if (
            strlen($this->session->getVar('userId')) >0 &&
            strlen($this->session->getVar('loginString')) >0
        ) { return true; }
        return false;
    }

    public function checkUserExists($email) {
        $stmt = $this->dbo->getDbo()->prepare("SELECT count(*) FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count);
        $stmt->fetch();
        if($count == 1) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    public function checkUserExistsByHash($hash) {
        $stmt = $this->dbo->getDbo()->prepare("SELECT email FROM users WHERE hash = ?");
        $stmt->bind_param('s', $hash);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();
        return $email;
    }

    private function checkPasswordCorrect($email, $password) {
        if($this->getUserHashedPassword($email) == $password ) { return true; }
        else { return false; }
    }

    private function getUserHashedPassword($email) {
        // Using prepared Statements means that SQL injection is not possible.
        if ($stmt = $this->dbo->getDbo()->prepare("SELECT password FROM users WHERE email = ?")) {
            $stmt->bind_param('s', $email); // Bind "$email" to parameter.
            $stmt->execute(); // Execute the prepared query.
            $stmt->store_result();
            $stmt->bind_result($hashPass); // get variables from result.
            $stmt->fetch();
            $stmt->close();
            return $hashPass;
        }
    }

    private function setUserLoginSession(){
        $this->session->setVar('userId', $this->idHash);
        $this->session->setVar('loginString', hash('sha512', $this->idHash.$this->ip_address.$this->user_browser));
        return true;
    }

    private function recordLoginTimeStamp() {
        // todo
    }

    private function loginCheck(){
        // todo refactor
        if ($email = $this->checkUserExistsByHash( $this->session->getVar('userId') ) ) {
            $this->loadUser($email);

            $login_check = hash('sha512', $this->idHash.$this->ip_address.$this->user_browser);
            if ($login_check == $this->session->getVar('loginString')) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function logout(){
        $this->session->reset();
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        $this->session->destroy();
    }

}