<?php

class EbayCodePracticeControllerBaseClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testControllerBaseInstantiates() {
        $controllerBaseObject = new \Controller\Base() ;
        $this->assertTrue( is_object($controllerBaseObject) );
    }

    public function testControllerBaseHasContentProperty() {
        $controllerBaseObject = new Controller\Base() ;
        $this->assertTrue( property_exists($controllerBaseObject,'content') );
    }

    public function testControllerBaseHasContentPropertyWithEmptyArrayValueOnInstantiation() {
        $controllerBaseObject = new Controller\Base() ;
        $reflectionObject = new ReflectionObject($controllerBaseObject);
        $controllerBaseContentProperty = $reflectionObject->getProperty('content');
        $controllerBaseContentProperty->setAccessible(true);
        $controllerBaseContentPropertyValue = $controllerBaseContentProperty->getValue($controllerBaseObject);
        $this->assertSame( $controllerBaseContentPropertyValue, array() );
    }

    public function testInitUserSetsContentUserDataVarAsObject() {
        $controllerBaseObject = new Controller\Base() ;
        $controllerBaseObject->initUser() ;
        $reflectionObject = new ReflectionObject($controllerBaseObject);
        $controllerBaseContentProperty = $reflectionObject->getProperty('content');
        $controllerBaseContentProperty->setAccessible(true);
        $controllerBaseContentPropertyValue = $controllerBaseContentProperty->getValue($controllerBaseObject);
        $this->assertTrue( is_object ($controllerBaseContentPropertyValue["userData"]) );
    }

    public function testInitUserStartsTheUserSession() {
        $mockUserSession = $this->getMockBuilder('\Model\UserSession')
                                ->disableOriginalConstructor()
                                ->getMock();
        $mockUserSession->expects($this->once())->method('startUserSession');
        $controllerBaseObject = new Controller\Base() ;
        $reflectionObject = new ReflectionObject($controllerBaseObject);
        $controllerBaseContentProperty = $reflectionObject->getProperty('content');
        $controllerBaseContentProperty->setAccessible(true);
        $controllerBaseContentPropertyValue = $controllerBaseContentProperty->getValue($controllerBaseObject);
        $controllerBaseContentPropertyValue["userSession"] = $mockUserSession;
        $controllerBaseContentProperty->setValue($controllerBaseObject, $controllerBaseContentPropertyValue);
        $controllerBaseObject->initUser() ;
    }

    public function testInitUserSetsContentUserDataVarAsObjectOfUserDataType() {
        $controllerBaseObject = new Controller\Base() ;
        $controllerBaseObject->initUser() ;
        $reflectionObject = new ReflectionObject($controllerBaseObject);
        $controllerBaseContentProperty = $reflectionObject->getProperty('content');
        $controllerBaseContentProperty->setAccessible(true);
        $controllerBaseContentPropertyValue = $controllerBaseContentProperty->getValue($controllerBaseObject);
        $this->assertInstanceOf( '\Model\UserData', $controllerBaseContentPropertyValue["userData"] );
    }

    public function testInitUserSetsContentUserSessionVarAsObject() {
        $controllerBaseObject = new Controller\Base() ;
        $controllerBaseObject->initUser() ;
        $reflectionObject = new ReflectionObject($controllerBaseObject);
        $controllerBaseContentProperty = $reflectionObject->getProperty('content');
        $controllerBaseContentProperty->setAccessible(true);
        $controllerBaseContentPropertyValue = $controllerBaseContentProperty->getValue($controllerBaseObject);
        $this->assertTrue( is_object ($controllerBaseContentPropertyValue["userSession"]) );
    }

    public function testInitUserSetsContentUserSessionVarAsObjectOfUserSessionType() {
        $controllerBaseObject = new Controller\Base() ;
        $controllerBaseObject->initUser() ;
        $reflectionObject = new ReflectionObject($controllerBaseObject);
        $controllerBaseContentProperty = $reflectionObject->getProperty('content');
        $controllerBaseContentProperty->setAccessible(true);
        $controllerBaseContentPropertyValue = $controllerBaseContentProperty->getValue($controllerBaseObject);
        $this->assertInstanceOf( '\Model\UserSession', $controllerBaseContentPropertyValue["userSession"] );
    }

    public function testInitUserCheckUserExistence() {
        $mockUserData = $this->getMockBuilder('\Model\UserData')
            ->disableOriginalConstructor()
            ->getMock();
        $mockUserData->expects($this->once())->method('checkUserExistsByHash');
        $controllerBaseObject = new Controller\Base() ;
        $reflectionObject = new ReflectionObject($controllerBaseObject);
        $controllerBaseContentProperty = $reflectionObject->getProperty('content');
        $controllerBaseContentProperty->setAccessible(true);
        $controllerBaseContentPropertyValue = $controllerBaseContentProperty->getValue($controllerBaseObject);
        $controllerBaseContentPropertyValue["userData"] = $mockUserData;
        $controllerBaseContentProperty->setValue($controllerBaseObject, $controllerBaseContentPropertyValue);
        $controllerBaseObject->initUser() ;
    }

    public function testInitUseLoadsAUser() {
        $mockUserData = $this->getMockBuilder('\Model\UserData')
            ->disableOriginalConstructor()
            ->getMock();
        $mockUserData->expects($this->once())->method('loadUser');
        $controllerBaseObject = new Controller\Base() ;
        $reflectionObject = new ReflectionObject($controllerBaseObject);
        $controllerBaseContentProperty = $reflectionObject->getProperty('content');
        $controllerBaseContentProperty->setAccessible(true);
        $controllerBaseContentPropertyValue = $controllerBaseContentProperty->getValue($controllerBaseObject);
        $controllerBaseContentPropertyValue["userData"] = $mockUserData;
        $controllerBaseContentProperty->setValue($controllerBaseObject, $controllerBaseContentPropertyValue);
        $controllerBaseObject->initUser() ;
    }

    public function testCheckIfFormPostedReturnsAValue() {
        $controllerBaseObject = new Controller\Base() ;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertNotNull ($controllerBaseObject->checkIfFormPosted() );
    }

    public function testCheckIfFormPostedReturnsAValueOfBooleanType() {
        $controllerBaseObject = new Controller\Base() ;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertTrue ( is_bool($controllerBaseObject->checkIfFormPosted() ) );
    }

    public function testCheckIfFormPostedReturnsAValueWithoutPost() {
        $controllerBaseObject = new Controller\Base() ;
        $this->assertNotNull ($controllerBaseObject->checkIfFormPosted() );
    }

    public function testCheckIfFormPostedReturnsAValueOfBooleanTypeWithoutPost() {
        $controllerBaseObject = new Controller\Base() ;
        $this->assertTrue ( is_bool($controllerBaseObject->checkIfFormPosted() ) );
    }

    public function testCheckIfFormPostedReturnsValueWithAnyPost() {
        $controllerBaseObject = new Controller\Base() ;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertNotNull ($controllerBaseObject->checkIfFormPosted() );
    }

    public function testCheckIfFormPostedReturnsValueWithMockPost() {
        $controllerBaseObject = new Controller\Base() ;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_REQUEST["formId"] = 'loginForm';
        $this->assertNotNull ($controllerBaseObject->checkIfFormPosted('loginForm') );
    }

    public function testCheckIfFormPostedReturnsAValueOfBooleanTypeWithPost() {
        $controllerBaseObject = new Controller\Base() ;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertTrue ( is_bool($controllerBaseObject->checkIfFormPosted() ) );
    }

    public function testCheckIfFormPostedReturnsTrueIfFormIsPosted() {
        $controllerBaseObject = new Controller\Base() ;
        $controllerBaseObject->initUser() ;
        $reflectionObject = new ReflectionObject($controllerBaseObject);
        $controllerBaseContentProperty = $reflectionObject->getProperty('content');
        $controllerBaseContentProperty->setAccessible(true);
        $controllerBaseContentPropertyValue = $controllerBaseContentProperty->getValue($controllerBaseObject);
        $this->assertInstanceOf( '\Model\UserData', $controllerBaseContentPropertyValue["userData"] );
    }

}
