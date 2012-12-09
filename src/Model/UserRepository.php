<?php

Namespace Model;

class UserRepository {

    private	$dbo;

	public function __construct() {
        $this->dbo = new \Core\Database();
	}

    public function findAllUsers() {
        $result = $this->dbo->doQuery('SELECT * FROM users');
    }

    public function getUserById() {
        // todo  $this->isLoggedIn = () ?  ;
    }

}