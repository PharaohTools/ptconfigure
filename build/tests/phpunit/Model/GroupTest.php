<?php

class EbayCodePracticeModelGroupClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    private function getRandomGroupId(){
        $dbo = bootStrapForTests::getMysqlI();
        $allGroupIds = array();
        $result = $dbo->query('SELECT id FROM groups');
        while ($row = $result->fetch_row()) {
            $allGroupIds[] = $row[0]; }
        return array_rand($allGroupIds); }

    private function getRandomRoleId(){
        $dbo = bootStrapForTests::getMysqlI();
        $allRoleIds = array();
        $result = $dbo->query('SELECT id FROM roles');
        while ($row = $result->fetch_row()) {
            $allRoleIds[] = $row[0]; }
        return array_rand($allRoleIds); }

    private function createTestNonAdminGroup() {
        $dbo = bootStrapForTests::getMysqlI();
        $groupName = "testingGroup";
        $query = 'INSERT INTO groups (`id`, `name`) VALUES ( ';
        $query .= 'NULL, "'.$groupName.'") ';
        $dbo->query($query);
    }

    private function createTestAdminGroup() {
        $dbo = bootStrapForTests::getMysqlI();
        $groupName = "testingAdminGroup";
        $query = 'INSERT INTO groups (`id`, `name`) VALUES ( ';
        $query .= 'NULL, "'.$groupName.'") ';
        $dbo->query($query);
        $query = 'SELECT id FROM groups WHERE name= "'.$groupName.'" ';
        $result = $dbo->query($query);
        $gid = $result->fetch_object();
        $query = 'INSERT INTO groupRoles (gid, rid) VALUES ( ';
        $query .= '"'.$gid->id.'", "1" )';
        $dbo->query($query);
    }

    private function dropGroup($groupName) {
        $dbo = bootStrapForTests::getMysqlI();
        if (is_array($groupName)) {
            foreach ($groupName as $name) {
                $query = 'DELETE FROM groups WHERE name= "'.$name.'" ';
                $dbo->query($query); } }
        else {
            $query = 'DELETE FROM groups WHERE name= "'.$groupName.'" ';
            $dbo->query($query); } }

    private function dropAdminGroup($groupName= "testingAdminGroup") {
        $dbo = bootStrapForTests::getMysqlI();
        $query = 'SELECT id FROM groups WHERE name= "'.$groupName.'" ';
        $result = $dbo->query($query);
        $gid = $result->fetch_object();
        $query = 'DELETE FROM groupRoles WHERE gid= "'.$gid->id.'" ';
        $dbo->query($query);
        $this->dropGroup($groupName); }

    private function getExistingGroupsFixtures() {
        $dbo = bootStrapForTests::getMysqlI();
        $result = $dbo->query('SELECT name FROM groups');
        $resultsArray = array();
        while ($row = $result->fetch_row()) {
            $resultsArray[] = $row[0]; }
        return $resultsArray; }

    private function getTestGroupsFixtures() {
        $resultsArray = array("Test Group 1", "TestingGroupName", "agreatgroup", "Pussycat Dolls");
        return $resultsArray; }

    public function testSetNewGroupSetsGroupNamePropertyForExistingGroups() {
        $existingGroupFixtures = $this->getExistingGroupsFixtures();
        foreach ($existingGroupFixtures as $existingGroupFixture) {
            $group = new \Model\Group("disabled") ;
            $group->setNewGroup($existingGroupFixture);
            $reflector = new ReflectionProperty($group, "groupName");
            $reflector->setAccessible(true);
            $groupNameValueInObject = $reflector->getValue($group);
            $this->assertSame( $groupNameValueInObject, $existingGroupFixture ); } }

    public function testSetNewGroupSetsGroupNamePropertyForTestGroups() {
        $testGroupFixtures = $this->getTestGroupsFixtures();
        foreach ($testGroupFixtures as $testGroupFixture) {
            $group = new \Model\Group("disabled") ;
            $group->setNewGroup($testGroupFixture);
            $reflector = new ReflectionProperty($group, "groupName");
            $reflector->setAccessible(true);
            $groupNameValueInObject = $reflector->getValue($group);
            $this->assertSame( $groupNameValueInObject, $testGroupFixture ); }
        $this->dropGroup($testGroupFixtures); }

    public function testisAdminReturnsBoolean() {
        $group = new \Model\Group() ;
        $this->assertTrue ( is_bool($group->isAdmin()) ); }

    public function testisAdminReturnsFalseIfNoGroupLoaded() {
        $group = new \Model\Group() ;
        $this->assertFalse ( $group->isAdmin() ); }

    public function testisAdminReturnsBooleanIfNonAdminGroupLoaded() {
        $this->createTestNonAdminGroup();
        $group = new \Model\Group("disabled") ;
        $group->loadGroupByName("testingGroup");
        $this->assertTrue ( is_bool($group->isAdmin()) );
        $this->dropGroup("testingGroup"); }

    public function testisAdminReturnsFalseIfNonAdminGroupLoaded() {
        $this->createTestNonAdminGroup();
        $group = new \Model\Group("disabled") ;
        $group->loadGroupByName("testingGroup");
        $this->assertFalse ( $group->isAdmin() );
        $this->dropGroup("testingGroup"); }

    public function testisAdminReturnsTrueIfAdminGroupLoaded() {
        $this->createTestAdminGroup();
        $group = new \Model\Group("disabled") ;
        $group->loadGroupByName("testingAdminGroup");
        $this->assertTrue ( $group->isAdmin() );
        $this->dropAdminGroup("testingAdminGroup"); }

    public function testGetIdReturnsNullOrInteger() {
        $group = new \Model\Group("disabled") ;
        $id = $group->getId();
        $this->assertTrue( is_int($id) || is_null($id) ); }

    public function testloadGroupSetsGroupIdProperty() {
        $groupsFixtures = $this->getExistingGroupsFixtures();
        foreach ($groupsFixtures as $groupsFixture) {
            $group = new \Model\Group("disabled") ;
            $group->loadGroupByName($groupsFixture);
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT id FROM groups WHERE name = ? LIMIT 1");
            $stmt->bind_param('s', $groupsFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($queriedId);
            $stmt->fetch();
            $stmt->close();
            $this->assertAttributeEquals( $queriedId, 'id', $group ); } }

    public function testloadGroupSetsGroupNameProperty() {
        $groupsFixtures = $this->getExistingGroupsFixtures();
        foreach ($groupsFixtures as $groupsFixture) {
            $group = new \Model\Group("disabled") ;
            $group->loadGroupByName($groupsFixture);
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT name FROM groups WHERE name = ? LIMIT 1");
            $stmt->bind_param('s', $groupsFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($groupName);
            $stmt->fetch();
            $this->assertAttributeEquals( $groupName, 'groupName', $group ); } }


    public function testHasRoleWillReturnBoolean() {
        $group = new \Model\Group("disabled");
        $randomGroupId = $this->getRandomGroupId();
        $group->loadGroupById($randomGroupId);
        $role = new \Model\Role();
        $randomRoleId = $this->getRandomRoleId();
        $role->loadRoleById($randomRoleId);
        $returnValue = $group->hasRole($role);
        $this->assertTrue( is_bool($returnValue) );
    }

    public function testHasRoleWillReturnFalseWhenRoleNotAvailable() {
        $group = new \Model\Group("disabled");
        $randomGroupId = $this->getRandomGroupId();
        $group->loadGroupById($randomGroupId);
        $role = new \Model\Role();
        $randomRoleId = $this->getRandomRoleId();
        $role->loadRoleById($randomRoleId);
        $returnValue = $group->hasRole($role, new mockGroupRoleRelationUnAvailable());
        $this->assertTrue( is_bool($returnValue) );
    }

    public function testHasRoleWillReturnTrueWhenRoleAvailable() {
        $group = new \Model\Group("disabled");
        $randomGroupId = $this->getRandomGroupId();
        $group->loadGroupById($randomGroupId);
        $role = new \Model\Role();
        $randomRoleId = $this->getRandomRoleId();
        $role->loadRoleById($randomRoleId);
        $returnValue = $group->hasRole($role, new mockGroupRoleRelationAvailable());
        $this->assertTrue( is_bool($returnValue) );
    }

}

class mockGroupRoleRelationAvailable {
    public function exists() {
        return true; }
}

class mockGroupRoleRelationUnAvailable {
    public function exists() {
        return false; }
}