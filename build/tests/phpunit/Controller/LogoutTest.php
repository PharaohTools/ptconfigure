<?php

class EbayCodePracticeControllerLogoutClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testExecuteReturnsAValue() {
        $controlObject = new \Controller\Logout();
        $this->assertTrue ( $controlObject->execute( array() ) != null );
    }

    public function testExecuteReturnsAValueOfTypeArray() {
        $controlObject = new \Controller\Logout();
        $this->assertTrue ( is_array($controlObject->execute( array() ) ) );
    }

    public function testExecuteSetsLogoutSuccessMessageIfUserLoggedInAlready() {
        $controlObject = new \Controller\Logout();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLogoutClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $controlObject->execute( array() );
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $this->assertTrue ( in_array("You have been logged out successfully", $currentContentArray["messages"]) );
    }

    public function testExecuteSetsLogoutNonSuccessMessageIfUserNotLoggedIn() {
        $controlObject = new \Controller\Logout();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLogoutClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $controlObject->execute( array() );
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $this->assertTrue ( in_array("You cannot log out as you are not logged in", $currentContentArray["messages"]) );
    }

}


class mockLogoutClassUserObjectLoggedIn {

    public $content;

    public function __construct() {
        $this->content = array();
        $this->content["messages"] = array(); }

    public function getLoginStatus() {
        return true;}

    public function logoutUserSession() {}

}

class mockLogoutClassUserObjectLoggedOut {

    public $content;

    public function __construct() {
        $this->content = array();
        $this->content["messages"] = array(); }

    public function getLoginStatus() {
        return false;}

    public function logoutUserSession() {}

}

class mockLogoutForm {

    public $formRequest;

    public function getValidationResult() {
        return array("results"=>"true");}

}