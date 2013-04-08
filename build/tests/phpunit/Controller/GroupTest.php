<?php

class EbayCodePracticeControllerGroupPageClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testExecuteReturnsAValue() {
        $controlObject = new \Controller\GroupPage();
        $this->assertTrue ( $controlObject->execute( array() ) != null ); }

    public function testExecuteReturnsAValueOfTypeArray() {
        $controlObject = new \Controller\GroupPage();
        $this->assertTrue ( is_array($controlObject->execute( array() ) ) ); }

    public function testExecuteSetsArrayTypeKeyIfUserNotLoggedIn() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithoutRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("type", $returnValue) ); }

    public function testExecuteSetsTypeToControlIfUserNotLoggedIn() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithoutRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( $returnValue["type"]== "control" ); }

    public function testExecuteSetsControlKeyIfUserNotLoggedIn() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithoutRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("control", $returnValue ) ); }

    public function testExecuteSetsIndexControlIfUserNotLoggedIn() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithoutRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue (  $returnValue["control"] =="index" ); }

    public function testExecuteSetsPleaseLoginMessageIfUserNotLoggedIn() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithoutRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $controlObject->execute( array() );
        $contentValue = $contentReflectionProperty->getValue($controlObject);
        $this->assertTrue ( in_array("Please Login", $contentValue["messages"]) ); }

    public function testExecuteSetsArrayTypeKeyIfUserIsLoggedInAndHasRole() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("type", $returnValue ) ); }

    public function testExecuteSetsTypeToViewIfUserIsLoggedInAndHasRole() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( $returnValue["type"]== "view" ); }

    public function testExecuteSetsViewKeyIfUserIsLoggedInAndHasRole() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("view", $returnValue ) ); }

    public function testExecuteSetsGroupPageAllowedViewIfUserIsLoggedInAndHasRole() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue (  $returnValue["view"] == "groupPageAllowed" ); }

    public function testExecuteSetsArrayTypeKeyIfUserIsLoggedInAndHasNoRole() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithoutRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("type", $returnValue ) ); }

    public function testExecuteSetsTypeToControlIfUserIsLoggedInAndHasNoRole() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithoutRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( $returnValue["type"]== "view" ); }

    public function testExecuteSetsViewKeyIfUserIsLoggedInAndHasNoRole() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithoutRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("view", $returnValue ) ); }

    public function testExecuteSetsGroupPageDeniedViewIfUserIsLoggedInAndHasNoRole() {
        $controlObject = new \Controller\GroupPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userData"] = new \mockGroupPageClassUserDataObjectWithoutRole();
        $currentContentArray["userSession"] = new \mockGroupPageClassUserSessionObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue (  $returnValue["view"] == "groupPageDenied" ); }

}

class mockGroupPageClassUserSessionObjectLoggedIn {
    public function getLoginStatus() { return true;}
    public function logoutUserSession() {}
}

class mockGroupPageClassUserSessionObjectLoggedOut {
    public function getLoginStatus() { return false;}
    public function logoutUserSession() {}
}

class mockGroupPageClassUserDataObjectWithRole {
    public function hasRole() { return true;}
}

class mockGroupPageClassUserDataObjectWithoutRole {
    public function hasRole() { return false;}
}
