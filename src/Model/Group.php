<?php

Namespace Model;

class Group {

    private	$dbo;
    private	$id;
    private	$groupName;

    public function __construct($disabled=null) {
        if ($disabled==null) {
            $this->dbo = new \Core\Database();  }
    }

    public function getId() {
        return $this->id;
    }

    public function loadGroupByName($groupName, $dbo=null) {
        if ($dbo==null) {$dbo = new \Core\Database(); }
        $stmt = $dbo->getDbo()->prepare("SELECT * FROM groups WHERE name = ? LIMIT 1");
        $stmt->bind_param('s', $groupName);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->id, $this->groupName);
        $stmt->fetch();
    }

    public function loadGroupById($groupId, $dbo=null) {
        if ($dbo==null) {$dbo = new \Core\Database(); }
        $stmt = $dbo->getDbo()->prepare("SELECT * FROM groups WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $groupId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->id, $this->groupName);
        $stmt->fetch();
    }

    public function setNewGroup($groupName) {
        $this->groupName = $groupName;
        $this->save();
    }

    private function save($dbo=null) {
        if ($dbo==null) {$dbo = new \Core\Database(); }
        $stmt = $dbo->getDbo()->prepare("INSERT INTO groups (name) VALUES (?)");
        $stmt->bind_param('s', $this->groupName);
        $stmt->execute();
    }

    public function hasRole(Role $role, $relation=null) {
        if (!isset($relation) ) { $relation = new GroupRole($this, $role); }
        if ($relation->exists() || $this->isAdmin() ) {
            return true; }
        return false;
    }

    public function isAdmin() {
        $role = new \Model\Role();
        $role->loadRoleByName("Administrator");
        $relation = new GroupRole($this, $role);
        if ($relation->exists() ) {
            return true; }
        return false;
    }

}