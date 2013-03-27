<?php

class EbayCodePracticeControllerLoginClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testExecuteReturnsAValue() {
        $controlObject = new \Controller\Login();
        $this->assertTrue ( $controlObject->execute( array() ) != null );
    }

    public function testExecuteReturnsAValueOfTypeArray() {
        $controlObject = new \Controller\Login();
        $this->assertTrue ( is_array($controlObject->execute( array() ) ) );
    }

    public function testExecuteSetsMessageIfUserLoggedInAlready() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedIn();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $controlObject->execute( array() );
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $this->assertTrue ( in_array("You can't login, you're already logged in", $currentContentArray["messages"]) );
    }

    public function testExecuteReturnsLoginTypeViewArrayKeyIfFormIsNotSet() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = null;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("type", $returnValue) );
    }

    public function testExecuteReturnsLoginViewArrayValueIfFormIsNotSet() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = null;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["type"]=="view" );
    }

    public function testExecuteReturnsLoginViewArrayKeyIfFormIsNotSet() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = null;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("view", $returnValue) );
    }

    public function testExecuteReturnsLoginViewLoginArrayValueIfFormIsNotSet() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = null;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["view"]=="login" );
    }

    public function testExecuteReturnsLoginTypeViewArrayKeyIfFormIsSetAndFormResultsFalse() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = false;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("type", $returnValue) );
    }

    public function testExecuteReturnsLoginViewArrayValueIfFormIsSetAndFormResultsFalse() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = false;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["type"]=="view" );
    }

    public function testExecuteReturnsLoginViewArrayKeyIfFormIsSetAndFormResultsFalse() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = false;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("view", $returnValue) );
    }

    public function testExecuteReturnsLoginViewLoginArrayValueIfFormIsSetAndFormResultsFalse() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = false;
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["view"]=="login" );
    }

    public function testExecuteReturnsLoginTypeViewArrayKeyIfFormIsSetAndFormResultsTrue() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $currentContentArray["formResult"]["results"] = "true";
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("type", $returnValue) );
    }

    public function testExecuteReturnsTypeControlArrayValueIfFormIsSetAndFormResultsTrue() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $logFormReflectionProperty = new \ReflectionProperty($controlObject, 'loginForm');
        $logFormReflectionProperty->setAccessible(true);
        $logFormReflectionProperty->setValue($controlObject, new mockLoginForm() );
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["type"]=="control" );
    }

    public function testExecuteReturnsTypeControlArrayKeyIfFormIsSetAndFormResultsTrue() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $logFormReflectionProperty = new \ReflectionProperty($controlObject, 'loginForm');
        $logFormReflectionProperty->setAccessible(true);
        $logFormReflectionProperty->setValue($controlObject, new mockLoginForm() );
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( array_key_exists("control", $returnValue) );
    }

    public function testExecuteReturnsIndexControlArrayValueIfFormIsSetAndFormResultsTrue() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $regFormReflectionProperty = new \ReflectionProperty($controlObject, 'loginForm');
        $regFormReflectionProperty->setAccessible(true);
        $regFormReflectionProperty->setValue($controlObject, new mockLoginForm() );
        $returnValue = $controlObject->execute( array() );
        $this->assertTrue( $returnValue["control"]=="index" );
    }

    public function testExecuteReturnsSetContentMessageIfFormIsSetAndFormResultsTrue() {
        $controlObject = new \Controller\Login();
        $contentReflectionProperty = new \ReflectionProperty($controlObject, 'content');
        $contentReflectionProperty->setAccessible(true);
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $currentContentArray["userSession"] = new \mockLoginClassUserObjectLoggedOut();
        $currentContentArray["formSet"] = true;
        $currentContentArray["formResult"] = array();
        $contentReflectionProperty->setValue($controlObject, $currentContentArray);
        $regFormReflectionProperty = new \ReflectionProperty($controlObject, 'loginForm');
        $regFormReflectionProperty->setAccessible(true);
        $regFormReflectionProperty->setValue($controlObject, new mockLoginForm() );
        $controlObject->execute( array() );
        $currentContentArray = $contentReflectionProperty->getValue($controlObject);
        $this->assertTrue ( in_array("You are now logged in", $currentContentArray["messages"]) );
    }

}


class mockLoginClassUserObjectLoggedIn {
    public function getLoginStatus() {
        return true;}

}

class mockLoginClassUserObjectLoggedOut {
    public function getLoginStatus() {
        return false;}

}

class mockLoginForm {
    public $formRequest;
    public function getValidationResult() {
        return array("results"=>"true");}

}