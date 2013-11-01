<?php

class EbayCodePracticeModelRoleClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    private function createTestNonAdminRole() {


        $dbo = bootStrapForTests::getMysqlI();

        $roleName = "testingRole";

        $query = 'INSERT INTO roles (`id`, `name`) VALUES ( ';
        $query .= 'NULL, "'.$roleName.'") ';

        $dbo->query($query);
    }

    private function dropRole($roleName) {

        $dbo = bootStrapForTests::getMysqlI();
        if (is_array($roleName)) {
            foreach ($roleName as $name) {
                $query = 'DELETE FROM roles WHERE name= "'.$name.'" ';
                $dbo->query($query); } }
        else {
            $query = 'DELETE FROM roles WHERE name= "'.$roleName.'" ';
            $dbo->query($query); }
    }

    private function getExistingRoleNamesFixture() {

        $dbo = bootStrapForTests::getMysqlI();
        $result = $dbo->query('SELECT name FROM roles');
        $resultsArray = array();
        while ($row = $result->fetch_row()) {
            $resultsArray[] = $row[0]; }
        return $resultsArray;
    }

    private function getTestRoleNamesFixture() {
        $resultsArray = array("RoleModel", "daveTheAdonis", "superBeer", "Ternary Statement", "Telco Engine");
        return $resultsArray;
    }

    private function getExistingRoleIdsFixture() {

        $dbo = bootStrapForTests::getMysqlI();
        $result = $dbo->query('SELECT id FROM roles');
        $resultsArray = array();
        while ($row = $result->fetch_row()) {
            $resultsArray[] = $row[0]; }
        return $resultsArray;
    }

    public function testGetIdReturnsNullOrInteger() {
        $role = new \Model\Role() ;
        $id = $role->getId( $role );
        $this->assertTrue( is_int($id) || is_null($id) );
    }

    public function testLoadRoleByNameSetsRoleIdPropertyForExistingRole() {

        $rolesFixtures = $this->getExistingRoleNamesFixture();

        foreach ($rolesFixtures as $rolesFixture) {
            $role = new \Model\Role() ;
            $reflector = new ReflectionMethod($role, "loadRoleByName");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $role, array("name"=>$rolesFixture) );

            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT id FROM roles WHERE name = ? LIMIT 1");
            $stmt->bind_param('s', $rolesFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($queriedId);
            $stmt->fetch();
            $stmt->close();

            $this->assertAttributeEquals( $queriedId, 'id', $role );
        }

    }

    public function testloadRoleByNameSetsRoleNamePropertyForExistingRole() {

        $rolesFixtures = $this->getExistingRoleNamesFixture();

        foreach ($rolesFixtures as $rolesFixture) {
            $role = new \Model\Role() ;
            $reflector = new ReflectionMethod($role, "loadRoleByName");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $role, array("name"=>$rolesFixture) );

            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT name FROM roles WHERE name = ? LIMIT 1");
            $stmt->bind_param('s', $rolesFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($roleName);
            $stmt->fetch();

            $this->assertAttributeEquals( $roleName, 'name', $role );
        }

    }


    public function testloadRoleByIdSetsRoleIdPropertyForExistingRole() {

        $rolesFixtures = $this->getExistingRoleIdsFixture();

        foreach ($rolesFixtures as $rolesFixture) {
            $role = new \Model\Role() ;
            $reflector = new ReflectionMethod($role, "loadRoleById");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $role, array("id"=>$rolesFixture) );
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT id FROM roles WHERE id = ? LIMIT 1");
            $stmt->bind_param('i', $rolesFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($queriedId);
            $stmt->fetch();
            $stmt->close();
            $this->assertAttributeEquals( $queriedId, 'id', $role );
        }

    }

    public function testloadRoleByIdSetsRoleNamePropertyForExistingRole() {
        $rolesFixtures = $this->getExistingRoleIdsFixture();
        foreach ($rolesFixtures as $rolesFixture) {
            $role = new \Model\Role() ;
            $reflector = new ReflectionMethod($role, "loadRoleById");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $role, array("id"=>$rolesFixture) );
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT name FROM roles WHERE id = ? LIMIT 1");
            $stmt->bind_param('i', $rolesFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($roleName);
            $stmt->fetch();
            $this->assertAttributeEquals( $roleName, 'name', $role );
        }
    }

    public function testSetNewRoleSetsRoleNamePropertyForTestRoles() {

        $testRoleFixtures = $this->getTestRoleNamesFixture();

        foreach ($testRoleFixtures as $testRoleFixture) {
            $role = new \Model\Role() ;
            $role->setNewRole($testRoleFixture);
            $reflector = new ReflectionProperty($role, "name");
            $reflector->setAccessible(true);
            $roleNameValueInObject = $reflector->getValue($role);
            $this->assertSame( $roleNameValueInObject, $testRoleFixture );

            $this->dropRole($testRoleFixture);
        }

    }

}
