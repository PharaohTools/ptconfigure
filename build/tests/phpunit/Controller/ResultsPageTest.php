<?php

class EbayCodePracticeControllerResultsPageClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testExecuteReturnsAValue() {
        $controlObject = new \Controller\ResultsPage();
        $this->assertTrue ( $controlObject->execute( array() ) != null );
    }

    public function testExecuteReturnsAValueOfTypeArray() {
        $controlObject = new \Controller\ResultsPage();
        $this->assertTrue ( is_array($controlObject->execute( array() ) ) ); }

    public function testExecuteSetsArrayTypeKeyIfUserNotLoggedIn() {
        $controlObject = new \Controller\ResultsPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockResultsPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("type", $returnValue) ); }

    public function testExecuteSetsTypeToControlIfUserNotLoggedIn() {
        $controlObject = new \Controller\ResultsPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockResultsPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( $returnValue["type"]== "control" ); }

    public function testExecuteSetsControlKeyIfUserNotLoggedIn() {
        $controlObject = new \Controller\ResultsPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockResultsPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("control", $returnValue ) ); }

    public function testExecuteSetsIndexControlIfUserNotLoggedIn() {
        $controlObject = new \Controller\ResultsPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockResultsPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue (  $returnValue["control"] =="index" ); }

    public function testExecuteSetsPleaseLoginMessageIfUserNotLoggedIn() {
        $controlObject = new \Controller\ResultsPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockResultsPageClassUserObjectLoggedOut();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $controlObject->execute( array() );
        $contentValue = $contentReflectionProperty->getValue($controlObject);
        $this->assertTrue ( in_array("Please Login", $contentValue["messages"]) ); }

    public function testExecuteSetsArrayTypeKeyIfUserIsLoggedIn() {
        $controlObject = new \Controller\ResultsPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockResultsPageClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("type", $returnValue ) ); }

    public function testExecuteSetsTypeToControlIfUserIsLoggedIn() {
        $controlObject = new \Controller\ResultsPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockResultsPageClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( $returnValue["type"]== "view" ); }

    public function testExecuteSetsViewKeyIfUserIsLoggedIn() {
        $controlObject = new \Controller\ResultsPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockResultsPageClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue ( array_key_exists("view", $returnValue ) ); }

    public function testExecuteSetsResultsPageViewIfUserIsLoggedIn() {
        $controlObject = new \Controller\ResultsPage();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockResultsPageClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue (  $returnValue["view"] == "resultsPage" ); }

}

class mockResultsPageClassUserObjectLoggedIn {
    public function getLoginStatus() {
        return true;}
    public function logoutUserSession() {}
}

class mockResultsPageClassUserObjectLoggedOut {
    public function getLoginStatus() {
        return false;}
    public function logoutUserSession() {}
}