<?php

class EbayCodePracticeModelGroupRoleClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    private function getExistingGroupRoleRelationsFixtures() {
        $dbo = bootStrapForTests::getMysqlI();
        $result = $dbo->query('SELECT * FROM groupRoles');
        $resultsArray = array();
        while ($row = $result->fetch_row()) {
            $resultsArray[] = $row; }
        return $resultsArray;
    }

    private function getNonExistingGroupRoleRelationsFixtures() {
        $dbo = bootStrapForTests::getMysqlI();
        $resultsArray = array();
        $allGroupIds = array();
        $result = $dbo->query('SELECT id FROM groups');
        while ($row = $result->fetch_row()) {
            $allGroupIds[] = $row[0]; }
        $allRoleIds = array();
        $result = $dbo->query('SELECT id FROM roles');
        while ($row = $result->fetch_row()) {
            $allRoleIds[] = $row[0]; }
        foreach ($allGroupIds as $groupId) {
            foreach ($allRoleIds as $roleId) {
                $stmt = $dbo->prepare("SELECT count(*) FROM groupRoles WHERE gid = ? AND rid = ?");
                $stmt->bind_param('ii',$groupId, $roleId );
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($count);
                $stmt->fetch();
                if($count == 0) {
                    $miniRay = array();
                    $miniRay["gid"] = $groupId;
                    $miniRay["rid"] = $roleId;
                    $resultsArray[] = $miniRay; }
                $stmt->close(); } }
        return $resultsArray;
    }

    public function testTryToLoadRelationWillLoadRelationWhenRelationExists() {
        foreach ($this->getExistingGroupRoleRelationsFixtures() as $GRRelation) {
            $group = new \Model\Group();
            $group->loadGroupById($GRRelation["gid"]);
            $role = new \Model\Role();
            $role->loadRoleById($GRRelation["rid"]);
            $groupRole = new \Model\GroupRole($group, $role);
            $groupRoleGroupIdProperty = new ReflectionProperty($groupRole, "groupId");
            $groupRoleGroupIdProperty->setAccessible(true);
            $groupRoleGroupIdValue = $groupRoleGroupIdProperty->getValue();
            $groupRoleRoleIdProperty = new ReflectionProperty($groupRole, "roleId");
            $groupRoleRoleIdProperty->setAccessible(true);
            $groupRoleRoleIdValue = $groupRoleRoleIdProperty->getValue();
            $this->assertTrue( $groupRoleGroupIdValue == $GRRelation["gid"]);
            $this->assertTrue( $groupRoleRoleIdValue == $GRRelation["rid"]);
        }
    }

    public function testTryToLoadRelationWillNotLoadRelationWhenRelationDoesNotExists() {
        foreach ($this->getNonExistingGroupRoleRelationsFixtures() as $GRRelation) {
            $group = new \Model\Group();
            $group->loadGroupById($GRRelation["gid"]);
            $role = new \Model\Role();
            $role->loadRoleById($GRRelation["rid"]);
            $groupRole = new \Model\GroupRole($group, $role);
            $groupRoleGroupIdProperty = new ReflectionProperty($groupRole, "groupId");
            $groupRoleGroupIdProperty->setAccessible(true);
            $groupRoleGroupIdValue = $groupRoleGroupIdProperty->getValue();
            $groupRoleRoleIdProperty = new ReflectionProperty($groupRole, "roleId");
            $groupRoleRoleIdProperty->setAccessible(true);
            $groupRoleRoleIdValue = $groupRoleRoleIdProperty->getValue();
            $this->assertTrue( is_null($groupRoleGroupIdValue) );
            $this->assertTrue( is_null($groupRoleRoleIdValue) );
        }
    }

    public function testExistsWillReturnFalseIfNeitherGroupIdOrRoleIdPropertiesAreSet() {
        foreach ($this->getExistingGroupRoleRelationsFixtures() as $GRRelation) {
            $group = new \Model\Group();
            $group->loadGroupById($GRRelation["gid"]);
            $role = new \Model\Role();
            $role->loadRoleById($GRRelation["rid"]);
            $groupRole = new \Model\GroupRole($group, $role);
            $groupRoleGroupIdProperty = new ReflectionProperty($groupRole, "groupId");
            $groupRoleGroupIdProperty->setAccessible(true);
            $groupRoleGroupIdProperty->setValue($groupRole, null);
            $groupRoleRoleIdProperty = new ReflectionProperty($groupRole, "roleId");
            $groupRoleRoleIdProperty->setAccessible(true);
            $groupRoleRoleIdProperty->setValue($groupRole, null);
            $this->assertTrue($groupRole->exists()==false);
        }
    }

    public function testExistsWillReturnFalseIfGroupIdPropertyIsNotSet() {
        foreach ($this->getExistingGroupRoleRelationsFixtures() as $GRRelation) {
            $group = new \Model\Group();
            $group->loadGroupById($GRRelation["gid"]);
            $role = new \Model\Role();
            $role->loadRoleById($GRRelation["rid"]);
            $groupRole = new \Model\GroupRole($group, $role);
            $groupRoleGroupIdProperty = new ReflectionProperty($groupRole, "groupId");
            $groupRoleGroupIdProperty->setAccessible(true);
            $groupRoleGroupIdProperty->setValue($groupRole, null);
            $this->assertTrue($groupRole->exists()==false);
        }
    }

    public function testExistsWillReturnFalseIfRoleIdPropertyIsNotSet() {
        foreach ($this->getExistingGroupRoleRelationsFixtures() as $GRRelation) {
            $group = new \Model\Group();
            $group->loadGroupById($GRRelation["gid"]);
            $role = new \Model\Role();
            $role->loadRoleById($GRRelation["rid"]);
            $groupRole = new \Model\GroupRole($group, $role);
            $groupRoleRoleIdProperty = new ReflectionProperty($groupRole, "roleId");
            $groupRoleRoleIdProperty->setAccessible(true);
            $groupRoleRoleIdProperty->setValue($groupRole, null);
            $this->assertTrue($groupRole->exists()==false);
        }
    }

    public function testExistsWillReturnFalseIfGroupIdPropertyIsNotInteger() {
        foreach ($this->getExistingGroupRoleRelationsFixtures() as $GRRelation) {
            $group = new \Model\Group();
            $group->loadGroupById($GRRelation["gid"]);
            $role = new \Model\Role();
            $role->loadRoleById($GRRelation["rid"]);
            $groupRole = new \Model\GroupRole($group, $role);
            $groupRoleGroupIdProperty = new ReflectionProperty($groupRole, "groupId");
            $groupRoleGroupIdProperty->setAccessible(true);
            $groupRoleGroupIdProperty->setValue($groupRole, "Not Integer");
            $this->assertTrue($groupRole->exists()==false);
        }
    }

    public function testExistsWillReturnFalseIfRoleIdPropertyIsNotInteger() {
        foreach ($this->getExistingGroupRoleRelationsFixtures() as $GRRelation) {
            $group = new \Model\Group();
            $group->loadGroupById($GRRelation["gid"]);
            $role = new \Model\Role();
            $role->loadRoleById($GRRelation["rid"]);
            $groupRole = new \Model\GroupRole($group, $role);
            $groupRoleRoleIdProperty = new ReflectionProperty($groupRole, "roleId");
            $groupRoleRoleIdProperty->setAccessible(true);
            $groupRoleRoleIdProperty->setValue($groupRole, "Not Integer");
            $this->assertTrue($groupRole->exists()==false);
        }
    }

    public function testExistsWillReturnTrueIfGroupIdAndRoleIdPropertiesAreSetCorrectly() {
        foreach ($this->getExistingGroupRoleRelationsFixtures() as $GRRelation) {
            $group = new \Model\Group();
            $group->loadGroupById($GRRelation["gid"]);
            $role = new \Model\Role();
            $role->loadRoleById($GRRelation["rid"]);
            $groupRole = new \Model\GroupRole($group, $role);
            $this->assertTrue($groupRole->exists()==true);
        }
    }

}
