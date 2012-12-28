<?php

Namespace Model;

class Role {

    private	$dbo;
    private	$id;
    private $name;

	public function __construct() {
        $this->dbo = new \Core\Database();
	}

    public function getId() {
        return $this->id;
    }

    public function loadRoleByName($name) {
        $stmt = $this->dbo->getDbo()->prepare("SELECT id, name FROM roles WHERE name = ? LIMIT 1");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->id, $this->name);
        $stmt->fetch();
    }

    public function loadRoleById($rid) {
        $stmt = $this->dbo->getDbo()->prepare("SELECT id, name FROM roles WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $rid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->id, $this->name);
        $stmt->fetch();
    }

    public function setNewRole($name) {
        $this->name = $name;
        $this->save();
    }

    private function save() {
        $stmt = $this->dbo->getDbo()->prepare("INSERT INTO roles (`name`) VALUES ( ? ) ");
        $stmt->bind_param('s', $this->name);
        $stmt->execute();
    }

}