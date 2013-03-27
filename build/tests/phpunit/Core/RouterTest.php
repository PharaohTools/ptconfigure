<?php

class EbayCodePracticeCoreRouterClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testrouterObjectInstantiates() {
        $routerObject = new Core\Router() ;
        $this->assertTrue( is_object($routerObject) );
    }

    public function testrunReturnsAnArray() {
        $routerObject = new Core\Router() ;
        $this->assertTrue( is_array($routerObject->run() ) );
    }

    public function testrunReturnsAnArrayWithControlKey() {
        $routerObject = new Core\Router() ;
        $route = $routerObject->run() ;
        $this->assertTrue( array_key_exists('control', $route ) );
    }

    public function testrunReturnsAnArrayWithActionKey() {
        $routerObject = new Core\Router() ;
        $route = $routerObject->run() ;
        $this->assertTrue( array_key_exists('action', $route ) );
    }

    public function testrunReturnsAnArrayWithOnlyTwoKeys() {
        $routerObject = new Core\Router() ;
        $this->assertTrue( count($routerObject->run())==2 );
    }

    public function testmodelRouteIsAnObjectOfClassModelRouter() {
        $routerObject = new Core\Router() ;
        $this->assertInstanceOf('Core\\Router', $routerObject);
    }

    public function testrouterObjectHasAttributes() {
        $routerObject = new Core\Router() ;
        $this->assertObjectHasAttribute( 'route', $routerObject );
        $this->assertObjectHasAttribute( 'availableRoutes', $routerObject );
    }

    public function testrouterObjectHasAvailableRoutesAttributeOfArrayType() {
        $routerObject = new Core\Router() ;
        $reflectionObject = new ReflectionObject($routerObject);
        $availableRoutesProperty = $reflectionObject->getProperty('availableRoutes');
        $availableRoutesProperty->setAccessible(true);
        $availableRoutesPropertyValue = $availableRoutesProperty->getValue($routerObject);
        $this->assertTrue( is_array($availableRoutesPropertyValue) );
    }

    public function testsetRouteControllerSetsDefaulControlWithHappyDefaultRequest() {
        $_REQUEST["control"] = "index";
        $_REQUEST["action"] = "index";
        $route = $this->executeSetRouteFunctionAndGetRoute("setRouteController");
        $this->assertTrue( $route["control"]=="index" );
    }

    public function testsetRouteControllerSetsNonDefaultWithHappyNonDefaultRequest() {
        $_REQUEST["control"] = "register";
        $_REQUEST["action"] = "register";
        $route = $this->executeSetRouteFunctionAndGetRoute("setRouteController");
        $this->assertTrue( $route["control"]=="register" );
    }

    public function testsetRouteControllerSetsDefaultWithSadRequest() {
        $_REQUEST["control"] = "administrator";
        $_REQUEST["action"] = "hack";
        $route = $this->executeSetRouteFunctionAndGetRoute("setRouteController");
        $this->assertTrue( $route["control"]=="index" );
    }

    public function testsetRouteActionSetsDefaulActionWithHappyDefaultRequest() {
        $_REQUEST["control"] = "index";
        $_REQUEST["action"] = "index";
        $route = $this->executeSetRouteFunctionAndGetRoute("setRouteAction");
        $this->assertTrue( $route["action"]=="index" );
    }

    public function testsetRouteActionSetsNonDefaultWithHappyNonDefaultRequest() {
        $_REQUEST["control"] = "register";
        $_REQUEST["action"] = "register";
        $route = $this->executeSetRouteFunctionAndGetRoute("setRouteAction");
        $this->assertTrue( $route["action"]=="register" );
    }

    public function testsetRouteActionSetsDefaultWithSadRequest() {
        $_REQUEST["control"] = "administrator";
        $_REQUEST["action"] = "hack";
        $route = $this->executeSetRouteFunctionAndGetRoute("setRouteAction");
        $this->assertTrue( $route["action"]=="index" );
    }

    private function executeSetRouteFunctionAndGetRoute($targetFunction) {
        $routerObject = new Core\Router() ;
        if ($targetFunction == "setRouteController" || $targetFunction == "setRouteAction") {
            $methodReflector = new ReflectionMethod($routerObject, $targetFunction);
        } else {
            throw new Exception("Specified target function param must be either setRouteController or setRouteAction");
        }
        $methodReflector->setAccessible(true);
        $methodReflector->invoke($routerObject);
        $reflectionObject = new ReflectionObject($routerObject);
        $routeProperty = $reflectionObject->getProperty('route');
        $routeProperty->setAccessible(true);
        return $routeProperty->getValue($routerObject);
    }

}