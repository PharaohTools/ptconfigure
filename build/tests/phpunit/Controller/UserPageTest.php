<?php

class EbayCodePracticeControllerUserPageClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testExecuteReturnsAValue() {
        $controlObject = new \Controller\UserPage();
        $this->assertTrue ( $controlObject->execute( array() ) != null );
    }

    public function testExecuteReturnsAValueOfTypeArray() {
        $controlObject = new \Controller\UserPage();
        $this->assertTrue ( is_array($controlObject->execute( array() ) ) );
    }

    public function testExecuteSetsArrayTypeKeyIfUserNotLoggedIn() {
        $controlObject = new \Controller\UserPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockUserPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("type", $returnValue) );
    }

    public function testExecuteSetsTypeToControlIfUserNotLoggedIn() {
        $controlObject = new \Controller\UserPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockUserPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( $returnValue["type"]== "control" );
    }

    public function testExecuteSetsControlKeyIfUserNotLoggedIn() {
        $controlObject = new \Controller\UserPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockUserPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("control", $returnValue ) );
    }

    public function testExecuteSetsIndexControlIfUserNotLoggedIn() {
        $controlObject = new \Controller\UserPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockUserPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue (  $returnValue["control"] =="index" );
    }

    public function testExecuteSetsPleaseLoginMessageIfUserNotLoggedIn() {
        $controlObject = new \Controller\UserPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockUserPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $controlObject->execute( array() );
        $contentValue = $contentReflectionProperty->getValue($controlObject);
        $this->assertTrue ( in_array("Please Login", $contentValue["messages"]) );
    }

    public function testExecuteSetsArrayTypeKeyIfUserIsLoggedIn() {
        $controlObject = new \Controller\UserPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockUserPageClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("type", $returnValue ) );
    }

    public function testExecuteSetsTypeToControlIfUserIsLoggedIn() {
        $controlObject = new \Controller\UserPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockUserPageClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( $returnValue["type"]== "view" );
    }

    public function testExecuteSetsViewKeyIfUserIsLoggedIn() {
        $controlObject = new \Controller\UserPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockUserPageClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("view", $returnValue ) );
    }

    public function testExecuteSetsIndexControlIfUserIsLoggedIn() {
        $controlObject = new \Controller\UserPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockUserPageClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue (  $returnValue["view"] =="userPage" );
    }

}


class mockUserPageClassUserObjectLoggedIn {

    public $content;

    public function __construct() {
        $this->content = array();
        $this->content["messages"] = array(); }

    public function getLoginStatus() {
        return true;}

    public function logoutUserSession() {}

}

class mockUserPageClassUserObjectLoggedOut {

    public $content;

    public function __construct() {
        $this->content = array();
        $this->content["messages"] = array(); }

    public function getLoginStatus() {
        return false;}

    public function logoutUserSession() {}

}