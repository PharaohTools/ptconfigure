<?php

class EbayCodePracticeModelUserRoleClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    private function getEmailFixtures() {
        $dbo = bootStrapForTests::getMysqlI();
        $result = $dbo->query('SELECT email FROM users WHERE id > 0 ');
        $resultsArray = array();
        while ($row = $result->fetch_row()) {
            $resultsArray[] = $row[0]; }
        return $resultsArray;
    }

    private function getExistingUserRoleRelationsFixtures() {
        $dbo = bootStrapForTests::getMysqlI();
        $result = $dbo->query('SELECT uid, rid FROM userRoles');
        $resultsArray = array();
        while ($row = $result->fetch_row()) {
            $miniRay = array();
            $miniRay["uid"] = $row[0];
            $miniRay["rid"] = $row[1];
            $resultsArray[] = $miniRay; }
        return $resultsArray;
    }

    private function getUserMailFromId($id) {
        $dbo = bootStrapForTests::getMysqlI();
        $result = $dbo->query("SELECT email FROM users WHERE id=$id");
        $row = $result->fetch_row();
        return $row[0];
    }

    private function getNonExistingUserRoleRelationsFixtures() {
        $dbo = bootStrapForTests::getMysqlI();
        $resultsArray = array();
        $allUserIds = array();
        $result = $dbo->query('SELECT id FROM users');
        while ($row = $result->fetch_row()) {
            $allUserIds[] = $row[0]; }
        $allRoleIds = array();
        $result = $dbo->query('SELECT id FROM roles');
        while ($row = $result->fetch_row()) {
            $allRoleIds[] = $row[0]; }
        foreach ($allUserIds as $userId) {
            foreach ($allRoleIds as $roleId) {
                $stmt = $dbo->prepare("SELECT count(*) FROM userRoles WHERE uid = ? AND rid = ?");
                $stmt->bind_param('ii',$userId, $roleId );
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($count);
                $stmt->fetch();
                if($count == 0) {
                    $miniRay = array();
                    $miniRay["uid"] = $userId;
                    $miniRay["rid"] = $roleId;
                    $resultsArray[] = $miniRay; }
                $stmt->close(); } }
        return $resultsArray;
    }

    public function testTryToLoadRelationWillLoadRelationWhenRelationExists() {
        foreach ($this->getExistingUserRoleRelationsFixtures() as $URRelation) {
            $user = new \Model\UserData();
            $user->loadUser($this->getUserMailFromId($URRelation["uid"]));
            $role = new \Model\Role();
            $role->loadRoleById($URRelation["rid"]);
            $userRole = new \Model\UserRole($user, $role);
            $userRoleUserIdProperty = new ReflectionProperty($userRole, "userId");
            $userRoleUserIdProperty->setAccessible(true);
            $userRoleUserIdValue = $userRoleUserIdProperty->getValue($userRole);
            $userRoleRoleIdProperty = new ReflectionProperty($userRole, "roleId");
            $userRoleRoleIdProperty->setAccessible(true);
            $userRoleRoleIdValue = $userRoleRoleIdProperty->getValue($userRole);
            $this->assertTrue( $userRoleUserIdValue == $URRelation["uid"]);
            $this->assertTrue( $userRoleRoleIdValue == $URRelation["rid"]);
        }
    }

    public function testTryToLoadRelationWillNotLoadRelationWhenRelationExists() {
        foreach ($this->getEmailFixtures()as $emailFixture) {
            foreach ($this->getNonExistingUserRoleRelationsFixtures() as $URRelation) {
                $user = new \Model\UserData();
                $user->loadUser($emailFixture);
                $role = new \Model\Role();
                $role->loadRoleById($URRelation["rid"]);
                $userRole = new \Model\UserRole($user, $role);
                $userRoleUserIdProperty = new ReflectionProperty($userRole, "userId");
                $userRoleUserIdProperty->setAccessible(true);
                $userRoleUserIdValue = $userRoleUserIdProperty->getValue($userRole);
                $userRoleRoleIdProperty = new ReflectionProperty($userRole, "roleId");
                $userRoleRoleIdProperty->setAccessible(true);
                $userRoleRoleIdValue = $userRoleRoleIdProperty->getValue($userRole);
                $this->assertTrue( is_null($userRoleUserIdValue) );
                $this->assertTrue( is_null($userRoleRoleIdValue) );
            }
        }
    }



    public function testExistsWillReturnFalseIfNeitherUserIdOrRoleIdPropertiesAreSet() {
        foreach ($this->getExistingUserRoleRelationsFixtures() as $URRelation) {
            $user = new \Model\UserData();
            $user->loadUserById($URRelation["uid"]);
            $role = new \Model\Role();
            $role->loadRoleById($URRelation["rid"]);
            $userRole = new \Model\UserRole($user, $role);
            $userRoleUserIdProperty = new ReflectionProperty($userRole, "userId");
            $userRoleUserIdProperty->setAccessible(true);
            $userRoleUserIdProperty->setValue($userRole, null);
            $userRoleRoleIdProperty = new ReflectionProperty($userRole, "roleId");
            $userRoleRoleIdProperty->setAccessible(true);
            $userRoleRoleIdProperty->setValue($userRole, null);
            $this->assertTrue($userRole->exists()==false);
        }
    }

    public function testExistsWillReturnFalseIfUserIdPropertyIsNotSet() {
        foreach ($this->getExistingUserRoleRelationsFixtures() as $URRelation) {
            $user = new \Model\UserData();
            $user->loadUserById($URRelation["uid"]);
            $role = new \Model\Role();
            $role->loadRoleById($URRelation["rid"]);
            $userRole = new \Model\UserRole($user, $role);
            $userRoleUserIdProperty = new ReflectionProperty($userRole, "userId");
            $userRoleUserIdProperty->setAccessible(true);
            $userRoleUserIdProperty->setValue($userRole, null);
            $this->assertTrue($userRole->exists()==false);
        }
    }

    public function testExistsWillReturnFalseIfRoleIdPropertyIsNotSet() {
        foreach ($this->getExistingUserRoleRelationsFixtures() as $URRelation) {
            $user = new \Model\UserData();
            $user->loadUserById($URRelation["uid"]);
            $role = new \Model\Role();
            $role->loadRoleById($URRelation["rid"]);
            $userRole = new \Model\UserRole($user, $role);
            $userRoleRoleIdProperty = new ReflectionProperty($userRole, "roleId");
            $userRoleRoleIdProperty->setAccessible(true);
            $userRoleRoleIdProperty->setValue($userRole, null);
            $this->assertTrue($userRole->exists()==false);
        }
    }

    public function testExistsWillReturnFalseIfUserIdPropertyIsNotInteger() {
        foreach ($this->getExistingUserRoleRelationsFixtures() as $URRelation) {
            $user = new \Model\UserData();
            $user->loadUserById($URRelation["uid"]);
            $role = new \Model\Role();
            $role->loadRoleById($URRelation["rid"]);
            $userRole = new \Model\UserRole($user, $role);
            $userRoleUserIdProperty = new ReflectionProperty($userRole, "userId");
            $userRoleUserIdProperty->setAccessible(true);
            $userRoleUserIdProperty->setValue($userRole, "Not Integer");
            $this->assertTrue($userRole->exists()==false);
        }
    }

    public function testExistsWillReturnFalseIfRoleIdPropertyIsNotInteger() {
        foreach ($this->getExistingUserRoleRelationsFixtures() as $URRelation) {
            $user = new \Model\UserData();
            $user->loadUserById($URRelation["uid"]);
            $role = new \Model\Role();
            $role->loadRoleById($URRelation["rid"]);
            $userRole = new \Model\UserRole($user, $role);
            $userRoleRoleIdProperty = new ReflectionProperty($userRole, "roleId");
            $userRoleRoleIdProperty->setAccessible(true);
            $userRoleRoleIdProperty->setValue($userRole, "Not Integer");
            $this->assertTrue($userRole->exists()==false);
        }
    }

    public function testExistsWillReturnTrueIfUserIdAndRoleIdPropertiesAreSetCorrectly() {
        foreach ($this->getExistingUserRoleRelationsFixtures() as $URRelation) {
            $user = new \Model\UserData();
            $user->loadUserById($URRelation["uid"]);
            $role = new \Model\Role();
            $role->loadRoleById($URRelation["rid"]);
            $userRole = new \Model\UserRole($user, $role);
            $this->assertTrue($userRole->exists()==true);
        }
    }


}
