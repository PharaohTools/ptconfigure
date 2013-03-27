<?php

class EbayCodePracticeCoreValidationHelpersClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    private function getExistingEmailFixtures() {

        $dbo = bootStrapForTests::getMysqlI();
        $result = $dbo->query('SELECT email FROM users WHERE id > 0');
        $resultsArray = array();
        while ($row = $result->fetch_row()) {
            $resultsArray[] = $row[0]; }
        return $resultsArray;
    }
    private function getValidNonExistentEmailFixtures() {
        $resultsArray = array();
        $resultsArray[] = 'avalidnonexistent@fakemail.com';
        $resultsArray[] = 'yetmorevalidnonexistent@fakemail.com';
        $resultsArray[] = 'davekingofbritain@fakemail.com';
        $resultsArray[] = 'lastvalidnonexistent@fakemail.com';
        return $resultsArray;
    }
    private function getValidPasswordsFixtures() {
        $resultsArray = array();
        $resultsArray[] = 'Su4058fnhoiuwf98';
        $resultsArray[] = '<34598725£$%£^£';
        $resultsArray[] = 'terrapin';
        $resultsArray[] = 'MingeInsect';
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

    private function dropUser($email="testXZY30458@email.com") {
        $dbo = bootStrapForTests::getMysqlI();
        $query = 'DELETE FROM users WHERE email= "'.$email.'" ';
        $dbo->query($query);
    }

    public function testConstructorInitializesObject() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $this->assertTrue( is_object($validationHelpers) ) ;
    }

    public function testNotBlankReturnsTrueIfGivenStringParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>"testString") ;
        $this->assertTrue( array_key_exists("true", $validationHelpers->notBlank($options) ) ) ;
    }

    public function testNotBlankReturnsTrueIfGivenIntegerParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>3) ;
        $this->assertTrue( array_key_exists("true", $validationHelpers->notBlank($options) ) ) ;
    }

    public function testNotBlankReturnsTrueIfGivenZeroIntegerParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>0) ;
        $this->assertTrue( array_key_exists("true", $validationHelpers->notBlank($options) ) ) ;
    }

    public function testNotBlankReturnsFalseIfGivenEmptyStringParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>"") ;
        $this->assertTrue(  array_key_exists("false", $validationHelpers->notBlank($options) ) ) ;
    }

    public function testNotBlankReturnsFalseIfGivenNullParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>null) ;
        $this->assertTrue(  array_key_exists("false", $validationHelpers->notBlank($options) ) ) ;
    }

    public function testIsEmailAddressReturnsFalseIfGivenPlainStringParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>"testString") ;
        $this->assertTrue( array_key_exists("false", $validationHelpers->isEmailAddress($options) ) ) ;
    }

    public function testIsEmailAddressReturnsFalseIfGivenIntegerParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>1234) ;
        $this->assertTrue( array_key_exists("false", $validationHelpers->isEmailAddress($options) ) ) ;
    }

    public function testIsEmailAddressReturnsFalseIfGivenSpecialCharactersParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>'$%^&*()') ;
        $this->assertTrue( array_key_exists("false", $validationHelpers->isEmailAddress($options) ) ) ;
    }

    public function testIsEmailAddressReturnsFalseIfGivenEmailAddressWithoutTldParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>"dave@mail") ;
        $this->assertTrue( array_key_exists("false", $validationHelpers->isEmailAddress($options) ) ) ;
    }

    public function testIsEmailAddressReturnsFalseIfGivenEmailAddressWithoutUserParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>"@mail.com") ;
        $this->assertTrue( array_key_exists("false", $validationHelpers->isEmailAddress($options) ) ) ;
    }

    public function testIsEmailAddressReturnsFalseIfGivenEmailAddressTextMissingParameter() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("fieldValue"=>'@..') ;
        $this->assertTrue( array_key_exists("false", $validationHelpers->isEmailAddress($options) ) ) ;
    }

    public function testIsEmailAddressReturnsTrueIfGivenValidStructureEmailAddressParameter() {
        $emailsFixture = array(
            "damanshia@ebay.com",
            "phpengine@hotmail.co.uk",
            "amiga.amanshia@gmail.com"
        ) ;
        foreach ($emailsFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("true", $validationHelpers->isEmailAddress($options) ) ) ;
        }
    }

    public function testIsUniqueEmailReturnsFalseIfGivenEmailThatExists() {
        foreach ($this->getExistingEmailFixtures() as $emailFixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$emailFixture) ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->isUniqueEmail($options) ) ) ;
        }
    }

    public function testIsUniqueEmailReturnsTrueIfGivenEmailThatDoesNotExist() {
        foreach ($this->getValidNonExistentEmailFixtures() as $emailFixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$emailFixture) ;
            $this->assertTrue( array_key_exists("true", $validationHelpers->isUniqueEmail($options) ) ) ;
        }
    }

    public function testMoreThan6CharsReturnsFalseIfGivenPlainStringParameterLessThan6Chars() {
        $stringsFixture = array(
            "t",
            "ebay",
            "dave",
            "sight",
            "two"
        ) ;
        foreach ($stringsFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->moreThan6Chars($options) ) ) ;
        }
    }

    public function testMoreThan6CharsReturnsFalseIfGivenIntegerParameterLessThan6Chars() {
        $integersFixture = array(
            12,
            647,
            23464,
            2,
            6543
        ) ;
        foreach ($integersFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->moreThan6Chars($options) ) ) ;
        }
    }

    public function testMoreThan6CharsReturnsFalseIfGivenSpecialCharacterParameterLessThan6Chars() {
        $specialCharactersFixture = array(
            "*",
            "$%^",
            "£')(&",
            "<£",
            "^&*%%"
        ) ;
        foreach ($specialCharactersFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->moreThan6Chars($options) ) ) ;
        }
    }

    public function testMoreThan6CharsReturnsTrueIfGivenPlainStringParameterMoreThan6Chars() {
        $stringsFixture = array(
            "tasfjpoijsa",
            "ebaysds",
            "davedfgdethtehthtjyrjyrjyjrj",
            "sighrjrjrtt",
            "tthtrtrrthrthrhwo"
        ) ;
        foreach ($stringsFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("true", $validationHelpers->moreThan6Chars($options) ) ) ;
        }
    }

    public function testMoreThan6CharsReturnsTrueIfGivenIntegerParameterMoreThan6Chars() {
        $integersFixture = array(
            1232235235,
            647235474687,
            234667894,
            2877655,
            6546785685863
        ) ;
        foreach ($integersFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("true", $validationHelpers->moreThan6Chars($options) ) ) ;
        }
    }

    public function testMoreThan6CharsReturnsTrueIfGivenSpecialCharacterParameterMoreThan6Chars() {
        $specialCharactersFixture = array(
            "*********",
            "$%^$%^$%^$%^",
            "£')(&£')(&£')(&£')(&£')(&",
            "<£<£<£<£",
            "^&*%%^&*%%^&*%%^&*%%^&*%%^&*%%^&*%%^&*%%^&*%%^&*%%^&*%%^&*%%^&*%%"
        ) ;
        foreach ($specialCharactersFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("true", $validationHelpers->moreThan6Chars($options) ) ) ;
        }
    }


    public function testLessThan10CharsReturnsFalseIfGivenPlainStringParameterMoreThan10Chars() {
        $stringsFixture = array(
            "tsadophvciaspovhjsidjvnsdipajnpisdaonjv",
            "ebayebayebay",
            "daveelevens",
            "sightoawifsh owifhiofhioh foihfi ohjopfhop",
            "twothreefourfive"
        ) ;
        foreach ($stringsFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->lessThan10Chars($options) ) ) ;
        }
    }

    public function testLessThan10CharsReturnsFalseIfGivenIntegerParameterMoreThan10Chars() {
        $integersFixture = array(
            12790238790874929,
            647234233223,
            2346456857685685865478457,
            245544567447,
            654365748458486346347
        ) ;
        foreach ($integersFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->lessThan10Chars($options) ) ) ;
        }
    }

    public function testLessThan10CharsReturnsFalseIfGivenSpecialCharacterParameterMoreThan10Chars() {
        $specialCharactersFixture = array(
            "£')(&*£')(&*£')(&*£')(&*£')(&*",
            "$%^$%^$%^$%^$%^",
            "£')(&£')(&£')(&£')(&£')(&£')(&£')(&£')(&£')(&£')(&£')(&£')(&£')(&£')(&",
            "<£^&*£')(&%%<£^&*£')(&%%",
            "^<£^&*£')(&%%<£^&*£')(&%%<£^&*£')(&%%<£^&*£')(&%%&*%%"  ) ;
        foreach ($specialCharactersFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->lessThan10Chars($options) ) ) ; }
    }

    public function testLessThan10CharsReturnsTrueIfGivenPlainStringParameterLessThan10Chars() {
        $stringsFixture = array(
            "buytitnow",
            "bidding",
            "selling",
            "paypal",
            "p"
        ) ;
        foreach ($stringsFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("true", $validationHelpers->lessThan10Chars($options) ) ) ;
        }
    }

    public function testLessThan10CharsReturnsTrueIfGivenIntegerParameterLessThan10Chars() {
        $integersFixture = array(
            1,
            64,
            2367894,
            28655,
            65863
        ) ;
        foreach ($integersFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("true", $validationHelpers->lessThan10Chars($options) ) ) ;
        }
    }

    public function testLessThan10CharsReturnsTrueIfGivenSpecialCharacterParameterLessThan10Chars() {
        $specialCharactersFixture = array(
            "*****",
            "$%^$%^",
            "£')(&&",
            "<£<£<£",
            "^&*%%%"
        ) ;
        foreach ($specialCharactersFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture) ;
            $this->assertTrue( array_key_exists("true", $validationHelpers->lessThan10Chars($options) ) ) ;
        }
    }

    public function testMustMatchReturnsFalseIfStringsDoNotMatch() {
        $testStringsFixture = array(
            "this is not the same",
            "not the same either",
            "chuck norris lost to me at thumb war"
        ) ;
        foreach ($testStringsFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture,
                "targetValue"=>"a string we can use",
                "targetFieldName"=>"testTargetField") ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->mustMatch($options) ) ) ;
        }
    }

    public function testMustMatchReturnsTrueIfStringsDoMatch() {
        $testStringsFixture = array( "this is the same" ) ;
        foreach ($testStringsFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("fieldValue"=>$fixture, "targetValue"=>"this is the same") ;
            $this->assertTrue( array_key_exists("true", $validationHelpers->mustMatch($options) ) ) ; }
    }


    public function testValidUserLoginReturnsTrueIfDetailsAreCorrect() {
        $this->createTestNonAdminUser();
        $validationHelpers = new Core\ValidationHelpers() ;
        $options = array("email"=>"testXZY30458@email.com", "userPass"=>"password") ;
        $this->assertTrue( array_key_exists("true", $validationHelpers->validUserLogin($options) ) ) ;
        $this->dropUser();
    }

    public function testValidUserLoginReturnsFalseIfPasswordIsWrong() {
        $this->createTestNonAdminUser();
        $testPasswordFixture = $this->getValidPasswordsFixtures();
        foreach ($testPasswordFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("email"=>"testXZY30458@email.com", "userPass"=>$fixture) ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->validUserLogin($options) ) ) ;}
        $this->dropUser();
    }

    public function testValidUserLoginReturnsFalseIfEmailIsWrong() {
        $this->createTestNonAdminUser();
        $testEmailFixture = $this->getValidNonExistentEmailFixtures();
        foreach ($testEmailFixture as $fixture) {
            $validationHelpers = new Core\ValidationHelpers() ;
            $options = array("email"=>$fixture, "userPass"=>"password") ;
            $this->assertTrue( array_key_exists("false", $validationHelpers->validUserLogin($options) ) ) ; }
        $this->dropUser();
    }


}
