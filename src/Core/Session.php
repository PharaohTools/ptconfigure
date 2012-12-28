<?php

Namespace Core ;

class Session {

	public function __construct() {
		if (session_id()=='') {$this->ecpSessionStart();}
	}

	public function getVar($varName) {
		return (isset($_SESSION[$varName])) ? $_SESSION[$varName] : false ;
	}

	public function setVar($varName, $value) {
		$_SESSION[$varName] = $value;
	}

    public function reset() {
        $_SESSION = array();
    }

    public function destroy() {
        session_destroy();
    }

    private function ecpSessionStart() {
        $session_name = 'ecpSession'; // Set a custom session name
        $secure = false; // Set to true if using https.
        $httpOnly = true; // This stops javascript being able to access the session id.
        ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies.
        $cookieParams = session_get_cookie_params(); // Gets current cookies params.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httpOnly);
        session_name($session_name); // Sets the session name to the one set above.
        session_start(); // Start the php session
        session_regenerate_id(true); // regenerated the session, delete the old one.
    }

}