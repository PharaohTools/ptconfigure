<?php

Namespace Model;

class UserData {

    private	$dbo;
    private	$id;
    private $idHash;
    private $timeCreate;
    private $timeLogin;
    private	$userName;
    private	$email;
    private	$pWord;

	public function __construct() {
        $this->dbo = new \Core\Database(); }

    public function getId() {
        return $this->id; }

    public function getIdHash() {
        return $this->idHash; }

    public function loadUser($email) {
        $stmt = $this->dbo->getDbo()->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->id, $this->idHash, $this->timeCreate, $this->timeLogin, $this->userName, $this->email, $this->pWord);
        $stmt->fetch(); }

    public function loadUserById($id) {
        $stmt = $this->dbo->getDbo()->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->id, $this->idHash, $this->timeCreate, $this->timeLogin, $this->userName, $this->email, $this->pWord);
        $stmt->fetch(); }

    public function setNewUser($userName, $email, $password) {
        $this->userName = $userName;
        $this->idHash = $this->createHash();
        $this->timeCreate = time();
        $this->email = $email;
        $this->pWord = md5($password);
        if ($this->save() ) {return true;}
        return false; }

    private function save() {
        $query = 'INSERT INTO users (hash, timeCreate, timeLogin, userName, email, password) VALUES (?, ?, ?, ?, ? )';
        if ($stmt = $this->dbo->getDbo()->prepare($query)) {
            $stmt->bind_param('sisss', $this->idHash, $this->timeCreate, $this->timeLogin,
                                       $this->userName, $this->email, $this->pWord);
            if ($stmt->execute() ) {return true;}
            return false; }
        return false; }

    private function createHash(){
        //function from http://stackoverflow.com/questions/853813/how-to-create-a-random-string-using-php
        $random_string = "";
        $valid_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $num_valid_chars = strlen($valid_chars);
        for ($i = 0; $i < 32; $i++) {
            $random_pick = mt_rand(1, $num_valid_chars);
            $random_char = $valid_chars[$random_pick-1];
            $random_string .= $random_char; }
        return $random_string; }

    public function hasRole(Role $role, $relation=null) {
        if (!isset($relation) ) { $relation = new UserRole($this, $role); }
        if ($relation->exists() || $this->isAdmin() ) {
            return true; }
        return false; }

    public function isAdmin() {
        $role = new \Model\Role();
        $role->loadRoleByName("Administrator");
        $relation = new UserRole($this, $role);
        if ($relation->exists() ) {
            return true; }
        return false; }

    public function checkUserExists($email) {
        $stmt = $this->dbo->getDbo()->prepare("SELECT count(*) FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count);
        $stmt->fetch();
        if($count == 1) {
            $stmt->close();
            return true; }
        $stmt->close();
        return false; }

    public function checkUserExistsByHash($hash) {
        $stmt = $this->dbo->getDbo()->prepare("SELECT email FROM users WHERE hash = ?");
        $stmt->bind_param('s', $hash);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();
        return $email; }

    public function checkPasswordCorrect($email, $password, $unhashed=null) {
        if ($unhashed=="unhashed") {
           return ($this->getUserHashedPassword($email) == md5($password) ) ?  true : false;}
        return ($this->getUserHashedPassword($email) == $password ) ?  true : false;
    }

    private function getUserHashedPassword($email) {
        if ($stmt = $this->dbo->getDbo()->prepare("SELECT password FROM users WHERE email = ?")) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($hashPass);
            $stmt->fetch();
            $stmt->close();
            return $hashPass; }
    }

}