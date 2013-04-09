<?php

class EbayCodePracticeControllerRegisterClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testExecuteReturnsAValue() {
        $controlObject = new \Controller\Register();
        $this->assertTrue ( $controlObject->execute( array() ) != null );
    }

    public function testExecuteReturnsAValueOfTypeArray() {
        $controlObject = new \Controller\Register();
        $this->assertTrue ( is_array($controlObject->execute( array() ) ) );
    }


    public function testExecuteSetsMessageIfUserLoggedInAlready() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $controlObject->execute( array() );
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $this->assertTrue ( in_array("You cant register, you're already logged in", $currentContentArray["messages"]) );
    }

    public function testExecuteReturnsRegisterTypeViewArrayKeyIfFormIsNotSet() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = null;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("type", $returnValue) );
    }

    public function testExecuteReturnsRegisterViewArrayValueIfFormIsNotSet() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = null;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["type"]=="view" );
    }

    public function testExecuteReturnsRegisterViewArrayKeyIfFormIsNotSet() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = null;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("view", $returnValue) );
    }

    public function testExecuteReturnsRegisterViewRegisterArrayValueIfFormIsNotSet() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = null;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["view"]=="register" );
    }

    public function testExecuteReturnsRegisterTypeViewArrayKeyIfFormIsSetAndFormResultsFalse() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = false;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("type", $returnValue) );
    }

    public function testExecuteReturnsRegisterViewArrayValueIfFormIsSetAndFormResultsFalse() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = false;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["type"]=="view" );
    }

    public function testExecuteReturnsRegisterViewArrayKeyIfFormIsSetAndFormResultsFalse() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = false;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("view", $returnValue) );
    }

    public function testExecuteReturnsRegisterViewRegisterArrayValueIfFormIsSetAndFormResultsFalse() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = false;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["view"]=="register" );
    }

    public function testExecuteReturnsRegisterTypeViewArrayKeyIfFormIsSetAndFormResultsTrue() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = "true";
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("type", $returnValue) );
    }

    public function testExecuteReturnsRegisterViewArrayValueIfFormIsSetAndFormResultsTrue() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = "true";
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["type"]=="view" );
    }

    public function testExecuteReturnsRegisterViewArrayKeyIfFormIsSetAndFormResultsTrue() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = "true";
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("view", $returnValue) );
    }

    public function testExecuteReturnsRegisterViewRegisterArrayValueIfFormIsSetAndFormResultsTrue() {
        $controlObject = new \Controller\Register();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockRegisterClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $regFormReflectionProperty = new \ReflectionProperty($controlObject, 'registrationForm');
        $regFormReflectionProperty->setAccessible(true);
        $regFormReflectionProperty->setValue($controlObject, new mockRegistrationForm() );
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["view"]=="registerSuccess" );
    }

}

class mockRegisterClassUserObjectLoggedIn {
    public function getLoginStatus() {
        return true;}
}

class mockRegisterClassUserObjectLoggedOut {
    public function getLoginStatus() {
        return false;}
}

class mockRegistrationForm {
    public $formRequest;
    public function getValidationResult() {
        return array("results"=>"true");}
}