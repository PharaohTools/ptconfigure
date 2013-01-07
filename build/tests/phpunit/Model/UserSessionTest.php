<?php

class EbayCodePracticeModelUserSessionClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php"); }

    public function testgetUserIdReturnsNullIfNoUserIdIsSet() {
        $_SESSION["userId"] = null;
        $userSession = new \Model\UserSession();
        $this->assertTrue ($userSession->getUserId() == null );
    }

    public function testgetUserIdReturnsAnIdIfUserIdIsSet() {
        $_SESSION["userId"] = '12345678';
        $userSession = new \Model\UserSession();
        $this->assertTrue ($userSession->getUserId() == '12345678' );
    }

    public function testgetLoginStatusReturnsBoolean() {
        $user = new \Model\UserSession() ;
        $this->assertTrue ( is_bool($user->getLoginStatus()) );
    }

    public function testloginCheckReturnsFalseIfUserNotLoggedIn() {
        $user = new \Model\UserSession() ;

        $userLoginCheckReflectionMethod = new \ReflectionMethod($user, 'loginCheck');
        $userLoginCheckReflectionMethod->setAccessible(true);

        $this->assertFalse ( $userLoginCheckReflectionMethod->invokeArgs($user, array("madeUpNotLoggedInEmail")) );
    }

    public function testloginCheckReturnsTrueIfUserLoggedIn() {
        $user = new \Model\UserSession() ;

        $userLoginCheckReflectionMethod = new \ReflectionMethod($user, 'loginCheck');
        $userLoginCheckReflectionMethod->setAccessible(true);

        $this->assertTrue ( $userLoginCheckReflectionMethod->invokeArgs($user, array("madeUpLoggedInEmail", "Log")) );
    }

    public function testgetLoginStatusReturnsFalseIfUserNotLoggedIn() {
        /* @todo this test */
    }

    public function testgetLoginStatusReturnsTrueIfUserLoggedIn() {
        /* @todo this test */
    }

    public function testverifySessionUserReturnsFalseIfSessionAndObjectValuesDoNotMatch() {
        /* @todo this test */
    }

    public function testverifySessionUserReturnsTrueIfSessionAndObjectValuesMatch() {

        $testIp = "1.2.3.4" ;
        $testBrowser = "GrannyPants" ;

        $user = new \Model\UserSession() ;

        $userIpAddressReflectionProperty = new \ReflectionProperty($user, 'session');
        $userIpAddressReflectionProperty->setAccessible(true);
        $userIpAddressReflectionProperty->setValue($user, new mockSession() );

        $userIpAddressReflectionProperty = new \ReflectionProperty($user, 'ipAddress');
        $userIpAddressReflectionProperty->setAccessible(true);
        $userIpAddressReflectionProperty->setValue($user, $testIp );

        $userBrowserReflectionProperty = new \ReflectionProperty($user, 'userBrowser');
        $userBrowserReflectionProperty->setAccessible(true);
        $userBrowserReflectionProperty->setValue($user, $testBrowser);

        $userVSUReflectionMethod = new \ReflectionMethod($user, 'verifySessionUser');
        $userVSUReflectionMethod->setAccessible(true);

        $this->assertTrue ( $userVSUReflectionMethod->invoke($user) );

    }

    public function testlogoutUserSessionDeletesASession() {
        $_SESSION["userId"] = "TestIdForSession";
        $userSession = new \Model\UserSession();
        $userSession->logoutUserSession();
        $this->assertTrue ( !isset($_SESSION["userId"]) );
    }



}

class mockSession {

    public function getVar($varToGet) {

        $testIp = "1.2.3.4" ;
        $testBrowser = "GrannyPants" ;

        if ( $varToGet == "loginString" ) {
            return hash('sha512', "TestIdForSession".$testIp.$testBrowser);
        }
        if ( $varToGet == "userId" ) {
            return "TestIdForSession";
        }

    }

}