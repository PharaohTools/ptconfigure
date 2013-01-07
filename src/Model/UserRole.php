<?php

Namespace Model;

class UserRole {

    private	$dbo;
    private	$user;
    private $role;
    private	$userId;
    private $roleId;

	public function __construct(UserData $user, Role $role) {
        $this->dbo = new \Core\Database();
        $this->user = $user;
        $this->role = $role;
        $this->tryToLoadRelation();
	}

    private function tryToLoadRelation() {
        if ($this->checkRelationExists()) {
            $this->loadRelation(); }
    }

    private function checkRelationExists($dbo=null) {
        if ($dbo==null) {$dbo = $this->dbo; }
        $stmt = $dbo->getDbo()->prepare("SELECT count(*) FROM userRoles WHERE uid = ? AND rid = ?");
        $stmt->bind_param('ii', $this->user->getId(), $this->role->getId() );
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count);
        $stmt->fetch();
        if ($count == 1) {
            $stmt->close();
            return true; }
        $stmt->close();
        return false;
    }

    private function loadRelation() {
        $this->userId = $this->user->getId();
        $this->roleId =$this->role->getId();
    }

    public function exists() {
        if ( isset($this->userId) && is_int($this->userId) &&
             isset($this->roleId) && is_int($this->roleId) ) {
             return true; }
        return false;
    }

    /*
    private function save() {
        if ($this->checkRelationExists()==false) {
            $stmt = $this->dbo->getDbo()->prepare("INSERT INTO userRoles (uid, rid) VALUES ( ? , ? ) ");
            $stmt->bind_param('ii', $this->user->getId(), $this->role->getId() );
            $stmt->execute(); }
    }
    */

}