<?php

Namespace Model;

class GroupRole {

    private	$dbo;
    private	$group;
    private $role;
    private	$groupId;
    private $roleId;

	public function __construct(Group $group, Role $role) {
        $this->dbo = new \Core\Database();
        $this->group = $group;
        $this->role = $role;
        $this->tryToLoadRelation();
	}

    private function tryToLoadRelation() {
        if ($this->checkRelationExists()) {
            $this->loadRelation(); }
    }

    private function checkRelationExists(){
        $stmt = $this->dbo->getDbo()->prepare("SELECT count(*) FROM groupRoles WHERE gid = ? AND rid = ?");
        $stmt->bind_param('ii', $this->group->getId(), $this->role->getId() );
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if($count == 1) {
            return true;}
        return false;

    }

    private function loadRelation() {
        $this->groupId = $this->group->getId();
        $this->roleId =$this->role->getId();
    }

    public function exists() {
        if ( isset($this->groupId) && is_int($this->groupId) &&
             isset($this->roleId) && is_int($this->roleId) ) {
             return true; }
        return false;
    }

    /*
    private function save() {
        if ($this->checkRelationExists()==false) {
            $stmt = $this->dbo->getDbo()->prepare("INSERT INTO groupRoles (uid, rid) VALUES ( ? , ? ) ");
            $stmt->bind_param('ii', $this->group->getId(), $this->role->getId() );
            $stmt->execute(); }
    }
    */

}