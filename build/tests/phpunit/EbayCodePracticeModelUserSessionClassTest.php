<?php

class EbayCodePracticeModelUserSessionClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once("bootstrap.php"); }

    public function testgetLoginStatusReturnsBoolean() {
        $user = new \Model\UserSession() ;
        $this->assertTrue ( is_bool($user->getLoginStatus()) );
    }

}