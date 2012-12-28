<?php

class EbayCodePracticeControllerAdministerPermissionsClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once("bootstrap.php");
    }

    public function testExecuteReturnsAValue() {
        $controlObject = new \Controller\AdministerPermissions();
        $this->assertTrue ( $controlObject->execute( array() ) != null );
    }

    public function testExecuteReturnsAValueOfTypeArray() {
        $controlObject = new \Controller\AdministerPermissions();
        $this->assertTrue ( is_array($controlObject->execute( array() ) ) );
    }

}
