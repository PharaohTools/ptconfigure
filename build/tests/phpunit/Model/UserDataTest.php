<?php

class EbayCodePracticeModelUserClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php"); }

    private function getRandomUserEmail(){
        $dbo = bootStrapForTests::getMysqlI();
        $allUserMails = array();
        $result = $dbo->query('SELECT email FROM users');
        while ($row = $result->fetch_row()) {
            $allUserIds[] = $row[0]; }
        return array_rand($allUserMails); }

    private function getRandomRoleId(){
        $dbo = bootStrapForTests::getMysqlI();
        $allRoleIds = array();
        $result = $dbo->query('SELECT id FROM roles');
        while ($row = $result->fetch_row()) {
            $allRoleIds[] = $row[0]; }
        return array_rand($allRoleIds); }

    private function getEmailFixtures() {
        $dbo = bootStrapForTests::getMysqlI();
        $result = $dbo->query('SELECT * FROM users WHERE id > 0 ');
        $resultsArray = array();
        while ($row = $result->fetch_row()) {
            $resultsArray[] = $row[0]; }
        return $resultsArray;
    }

    private function createTestNonAdminUser() {
        $dbo = bootStrapForTests::getMysqlI();
        $userName = "testingUser";
        $email = "testXZY30458@email.com";
        $password = md5("password");
        $query = 'INSERT INTO users (`id`, `timeCreate`, `timeLogin`, `userName`, `email`, `password`) VALUES ( ';
        $query .= 'NULL, "'.time().'", "'.time().'", ';
        $query .= '"'.$userName.'", "'.$email.'", "'.$password.'"); ';
        $dbo->query($query);
    }

    private function createTestAdminUser() {
        $dbo = bootStrapForTests::getMysqlI();
        $userName = "testingAdminUser";
        $email = "testadminXZY30458@email.com";
        $password = md5("password");
        $query = 'INSERT INTO users (`id`, `timeCreate`, `timeLogin`, `userName`, `email`, `password`) VALUES ( ';
        $query .= 'NULL, "'.time().'", "'.time().'", ';
        $query .= '"'.$userName.'", "'.$email.'", "'.$password.'"); ';
        $dbo->query($query);
        $query = 'SELECT id FROM users WHERE email= "'.$email.'" ';
        $result = $dbo->query($query);
        $aid = $result->fetch_row();
        $query = 'INSERT INTO userRoles (`uid`, `rid`) VALUES ( ';
        $query .= '"'.$aid.'", NULL )';
        $dbo->query($query);
    }

    private function dropUser($email="testXZY30458@email.com") {
        $dbo = bootStrapForTests::getMysqlI();
        $query = 'DELETE FROM users WHERE email= "'.$email.'" ';
        $dbo->query($query);
    }

    public function testisAdminReturnsBoolean() {
        $user = new \Model\UserData() ;
        $this->assertTrue ( is_bool($user->isAdmin()) );
    }

    public function testisAdminReturnsFalseIfNoUserLoaded() {
        $user = new \Model\UserData() ;
        $this->assertFalse ( $user->isAdmin() );
    }

    public function testisAdminReturnsBooleanIfNonAdminUserLoaded() {
        $this->createTestNonAdminUser();
        $user = new \Model\UserData() ;
        $methodReflector = new ReflectionMethod($user, "loadUser");
        $methodReflector->setAccessible(true);
        $methodReflector->invokeArgs($user, array("testXZY30458@email.com") );
        $this->assertTrue( is_bool($user->isAdmin() ) );
        $this->dropUser();
    }

    public function testisAdminReturnsFalseIfNonAdminUserLoaded() {
        $this->createTestNonAdminUser();
        $user = new \Model\UserData() ;
        $methodReflector = new ReflectionMethod($user, "loadUser");
        $methodReflector->setAccessible(true);
        $methodReflector->invokeArgs($user, array("testXZY30458@email.com") );
        $this->assertFalse ( $user->isAdmin() );
        $this->dropUser();
    }

    public function testisAdminReturnsTrueIfAdminUserLoaded() {
        $user = new \Model\UserData() ;
        $this->assertTrue ( is_bool($user->isAdmin()) );
    }

    public function testloadUserSetsUserIdProperty() {
        $emailFixtures = $this->getEmailFixtures();
        foreach ($emailFixtures as $emailFixture) {
            $user = new \Model\UserData() ;
            $reflector = new ReflectionMethod($user, "loadUser");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $user, array("email"=>$emailFixture) );
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $emailFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($queriedId);
            $stmt->fetch();
            $stmt->close();
            $this->assertAttributeEquals( $queriedId, 'id', $user ); }
    }

    public function testloadUserSetsUserIdHashProperty() {
        $emailFixtures = $this->getEmailFixtures();
        foreach ($emailFixtures as $emailFixture) {
            $user = new \Model\UserData() ;
            $reflector = new ReflectionMethod($user, "loadUser");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $user, array("email"=>$emailFixture) );
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT hash FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $emailFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($queriedIdHash);
            $stmt->fetch();
            $stmt->close();
            $this->assertAttributeEquals( $queriedIdHash, 'idHash', $user ); }
    }

    public function testloadUserSetsUserTimestampCreationProperty() {
        $emailFixtures = $this->getEmailFixtures();
        foreach ($emailFixtures as $emailFixture) {
            $user = new \Model\UserData() ;
            $reflector = new ReflectionMethod($user, "loadUser");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $user, array("email"=>$emailFixture) );
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT timeCreate FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $emailFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($timeCreate);
            $stmt->fetch();
            $this->assertAttributeEquals( $timeCreate, 'timeCreate', $user ); }
    }

    public function testloadUserSetsUserTimestampLoginProperty() {
        $emailFixtures = $this->getEmailFixtures();
        foreach ($emailFixtures as $emailFixture) {
            $user = new \Model\UserData() ;
            $reflector = new ReflectionMethod($user, "loadUser");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $user, array("email"=>$emailFixture) );
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT timeLogin FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $emailFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($timeLogin);
            $stmt->fetch();
            $this->assertAttributeEquals( $timeLogin, 'timeLogin', $user );}
    }

    public function testloadUserSetsUserNameProperty() {
        $emailFixtures = $this->getEmailFixtures();
        foreach ($emailFixtures as $emailFixture) {
            $user = new \Model\UserData() ;
            $reflector = new ReflectionMethod($user, "loadUser");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $user, array("email"=>$emailFixture) );
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT userName FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $emailFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($userName);
            $stmt->fetch();
            $this->assertAttributeEquals( $userName, 'userName', $user ); }
    }

    public function testloadUserSetsUserEmailProperty() {
        $emailFixtures = $this->getEmailFixtures();
        foreach ($emailFixtures as $emailFixture) {
            $user = new \Model\UserData() ;
            $reflector = new ReflectionMethod($user, "loadUser");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $user, array("email"=>$emailFixture) );
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT email FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $emailFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($email);
            $stmt->fetch();
            $this->assertAttributeEquals( $email, 'email', $user ); }
    }

    public function testloadUserSetsUserPasswordProperty() {
        $emailFixtures = $this->getEmailFixtures();
        foreach ($emailFixtures as $emailFixture) {
            $user = new \Model\UserData() ;
            $reflector = new ReflectionMethod($user, "loadUser");
            $reflector->setAccessible(true);
            $reflector->invokeArgs ( $user, array("email"=>$emailFixture) );
            $mysqli = \bootStrapForTests::getMysqlI();
            $stmt = $mysqli->prepare("SELECT password FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $emailFixture);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($pWord);
            $stmt->fetch();
            $this->assertAttributeEquals( $pWord, 'pWord', $user ); }
    }

    public function testHasRoleWillReturnBoolean() {
        $user = new \Model\UserData();
        $email = $this->getRandomUserEmail();
        $user->loadUser($email);
        $role = new \Model\Role();
        $randomRoleId = $this->getRandomRoleId();
        $role->loadRoleById($randomRoleId);
        $returnValue = $user->hasRole($role);
        $this->assertTrue( is_bool($returnValue) );
    }

    public function testHasRoleWillReturnFalseWhenRoleNotAvailable() {
        $user= new \Model\UserData();
        $email = $this->getRandomUserEmail();
        $user->loadUser($email);
        $role = new \Model\Role();
        $randomRoleId = $this->getRandomRoleId();
        $role->loadRoleById($randomRoleId);
        $returnValue = $user->hasRole($role, new mockUserDataRoleRelationUnAvailable());
        $this->assertTrue( $returnValue==false );
    }

    public function testHasRoleWillReturnTrueWhenRoleAvailable() {
        $user= new \Model\UserData();
        $email = $this->getRandomUserEmail();
        $user->loadUser($email);
        $role = new \Model\Role();
        $randomRoleId = $this->getRandomRoleId();
        $role->loadRoleById($randomRoleId);
        $returnValue = $user->hasRole($role, new mockUserDataRoleRelationAvailable());
        $this->assertTrue( $returnValue==true );
    }

    public function testIsAdminWillReturnBoolean() {
        $user = new \Model\UserData();
        $email = $this->getRandomUserEmail();
        $user->loadUser($email);
        $returnValue = $user->isAdmin();
        $this->assertTrue( is_bool($returnValue) );
    }

    public function testIsAdminWillReturnFalseWhenRoleNotAvailable() {
        $user= new \Model\UserData();
        $email = $this->getRandomUserEmail();
        $user->loadUser($email);
        $returnValue = $user->isAdmin(new mockUserDataRoleRelationUnAvailable());
        $this->assertTrue( $returnValue==false );
    }

    public function testIsAdminWillReturnTrueWhenRoleAvailable() {
        $user= new \Model\UserData();
        $email = $this->getRandomUserEmail();
        $user->loadUser($email);
        $returnValue = $user->isAdmin(new mockUserDataRoleRelationAvailable());
        $this->assertTrue( $returnValue==true );
    }

    public function testSaveWillNotAddANewRecordToUserTableWithEmptyProperties() {
        $mysqli = \bootStrapForTests::getMysqlI();
        $stmt = $mysqli->prepare("SELECT count(*) FROM users");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($originalUserCount);
        $stmt->fetch();

        $user= new \Model\UserData();
        $userSaveReflectionMethod = new \ReflectionMethod($user, 'save');
        $userSaveReflectionMethod->setAccessible(true);
        $userSaveReflectionMethod->invoke($user);

        $mysqli = \bootStrapForTests::getMysqlI();
        $stmt = $mysqli->prepare("SELECT count(*) FROM users");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($newUserCount);
        $stmt->fetch();

        $this->assertTrue( $originalUserCount==$newUserCount );
    }

    public function testSaveWillNotAddANewRecordToUserTableWithDuplicateEmailProperty() {
        $mysqli = \bootStrapForTests::getMysqlI();
        $stmt = $mysqli->prepare("SELECT count(*) FROM users");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($originalUserCount);
        $stmt->fetch();

        $user= new \Model\UserData();
        $userSaveReflectionProperty = new \ReflectionProperty($user, 'email');
        $userSaveReflectionProperty->setAccessible(true);
        $userSaveReflectionProperty->setValue($user, 'damanshia@ebay.com');

        $userSaveReflectionMethod = new \ReflectionMethod($user, 'save');
        $userSaveReflectionMethod->setAccessible(true);
        $userSaveReflectionMethod->invoke($user);

        $mysqli = \bootStrapForTests::getMysqlI();
        $stmt = $mysqli->prepare("SELECT count(*) FROM users");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($newUserCount);
        $stmt->fetch();

        $this->assertTrue( $originalUserCount==$newUserCount );
    }

    public function testSaveWillAddANewRecordToUserTableWithCorrectParameters() {
        $mysqli = \bootStrapForTests::getMysqlI();
        $stmt = $mysqli->prepare("SELECT count(*) FROM users");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($originalUserCount);
        $stmt->fetch();

        $user= new \Model\UserData();

        $userHashReflectionProperty = new \ReflectionProperty($user, 'idHash');
        $userHashReflectionProperty->setAccessible(true);
        $userHashReflectionProperty->setValue($user, '88888888');

        $userTimeCreateReflectionProperty = new \ReflectionProperty($user, 'timeCreate');
        $userTimeCreateReflectionProperty->setAccessible(true);
        $userTimeCreateReflectionProperty->setValue($user, time() );

        $userTimeLoginReflectionProperty = new \ReflectionProperty($user, 'timeLogin');
        $userTimeLoginReflectionProperty->setAccessible(true);
        $userTimeLoginReflectionProperty->setValue($user, time() );

        $userUserNameReflectionProperty = new \ReflectionProperty($user, 'userName');
        $userUserNameReflectionProperty->setAccessible(true);
        $userUserNameReflectionProperty->setValue($user, "testingUser");

        $userEMailReflectionProperty = new \ReflectionProperty($user, 'email');
        $userEMailReflectionProperty->setAccessible(true);
        $userEMailReflectionProperty->setValue($user, 'testXZY30458@email.com');

        $userPasswordReflectionProperty = new \ReflectionProperty($user, 'pWord');
        $userPasswordReflectionProperty->setAccessible(true);
        $userPasswordReflectionProperty->setValue($user, md5("password"));

        $userSaveReflectionMethod = new \ReflectionMethod($user, 'save');
        $userSaveReflectionMethod->setAccessible(true);
        $userSaveReflectionMethod->invoke($user);

        $mysqli = \bootStrapForTests::getMysqlI();
        $stmt = $mysqli->prepare("SELECT count(*) FROM users");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($newUserCount);
        $stmt->fetch();

        $this->assertTrue( ($originalUserCount+1)==$newUserCount );

        $this->dropUser();
    }

    public function testCheckPasswordCorrectWillReturnFalseIfEmailDoesNotExistHashedPass() {
        $user= new \Model\UserData();
        $returnValue = $user->checkPasswordCorrect("aCompletelyMadeUpEmailAddress@YourMum.com", md5(""));
        $this->assertFalse($returnValue);
    }

    public function testCheckPasswordCorrectWillReturnFalseIfEmailDoesNotExistUnhashedPass() {
        $user= new \Model\UserData();
        $returnValue = $user->checkPasswordCorrect("aCompletelyMadeUpEmailAddress@YourMum.com", "", "unhashed");
        $this->assertFalse($returnValue);
    }

    public function testCheckPasswordCorrectWillReturnFalseIfPasswordIsWrongHashedPass() {
        $this->createTestNonAdminUser();
        $user= new \Model\UserData();
        $returnValue = $user->checkPasswordCorrect("damanshia@ebay.com", md5("madeUpPassWordFromYoMama2378223817049") );
        $this->assertFalse($returnValue);
        $this->dropUser();
    }

    public function testCheckPasswordCorrectWillReturnFalseIfPasswordIsWrongUnhashedPass() {
        $this->createTestNonAdminUser();
        $user= new \Model\UserData();
        $returnValue = $user->checkPasswordCorrect("damanshia@ebay.com", "madeUpPasFrmYoMam237sdvsd381049", "unhashed");
        $this->assertFalse($returnValue);
        $this->dropUser();
    }

    public function testCheckPasswordCorrectWillReturnTrueIfPasswordIsCorrectHashedPass() {
        $this->createTestNonAdminUser();
        $user= new \Model\UserData();
        $returnValue = $user->checkPasswordCorrect("testXZY30458@email.com", md5("password") );
        $this->assertTrue($returnValue);
        $this->dropUser();
    }

    public function testCheckPasswordCorrectWillReturnTrueIfPasswordIsCorrectUnhashedPass() {
        $this->createTestNonAdminUser();
        $user= new \Model\UserData();
        $returnValue = $user->checkPasswordCorrect("testXZY30458@email.com", "password", "unhashed");
        $this->assertTrue($returnValue);
        $this->dropUser();
    }

    public function testGetUserHashedPasswordWillReturnNullIfEmailDoesNotExist() {
        $this->createTestNonAdminUser();
        $user= new \Model\UserData();
        $userSaveReflectionMethod = new \ReflectionMethod($user, 'getUserHashedPassword');
        $userSaveReflectionMethod->setAccessible(true);
        $returnValue = $userSaveReflectionMethod->invokeArgs($user, array("aCompletelyMadeUpEmailAddress@YourMum.com") );
        $this->assertTrue(is_null($returnValue));
        $this->dropUser();
    }

    public function testGetUserHashedPasswordWillReturnStringIfEmailDoesExist() {
        $this->createTestNonAdminUser();
        $user= new \Model\UserData();
        $userSaveReflectionMethod = new \ReflectionMethod($user, 'getUserHashedPassword');
        $userSaveReflectionMethod->setAccessible(true);
        $returnValue = $userSaveReflectionMethod->invokeArgs($user, array("testXZY30458@email.com") );
        $this->assertTrue(is_string($returnValue));
        $this->dropUser();
    }

    public function testGetUserHashedPasswordWillReturnStringOfCorrectValueIfEmailDoesExist() {
        $this->createTestNonAdminUser();
        $user= new \Model\UserData();
        $userSaveReflectionMethod = new \ReflectionMethod($user, 'getUserHashedPassword');
        $userSaveReflectionMethod->setAccessible(true);
        $returnValue = $userSaveReflectionMethod->invokeArgs($user, array("testXZY30458@email.com") );
        $this->assertTrue($returnValue == md5("password") );
        $this->dropUser();
    }

}

class mockUserDataRoleRelationAvailable {
    public function exists() {
        return true; }
}

class mockUserDataRoleRelationUnAvailable {
    public function exists() {
        return false; }
}

class fakeDbo {
    public function getDbo(){
        $mysqli = \bootStrapForTests::getMysqlI();
        return $mysqli ;
    }

}