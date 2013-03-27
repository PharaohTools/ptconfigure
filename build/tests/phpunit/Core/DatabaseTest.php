<?php

class EbayCodePracticeCoreDatabaseClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testconstructorInitializesObject() {
        $database = new Core\Database() ;
        $this->assertTrue( is_object($database) ) ;
    }

    public function testrunDbThrowsExceptionWhenCantConnectToDb() {

        $this->setExpectedException('Exception');

        $overrides = array();

        $overrides["dbHost"] = "localhost";
        $overrides["dbUser"] = "madeUpUser";
        $overrides["dbPass"] = "ebayebay";
        $overrides["dbName"] = "wrongdb";

        $database = new Core\Database("manual") ;
        $dboReflectionMethod = new ReflectionMethod($database, 'setConnectionVars');
        $dboReflectionMethod->setAccessible(true);
        $dboReflectionMethod->invokeArgs($database, array($overrides) );
        $database->runDb();
    }

    public function testconstructorThrowsExceptionWhenDbErrOrNoIsSet() {

        $this->setExpectedException('Exception');

        $database = new Core\Database() ;

        $dbUserReflectionProperty = new ReflectionProperty($database, 'dbUser');
        $dbUserReflectionProperty->setAccessible(true);
        $dbUserReflectionProperty->setValue($database, "madeUpUser");

        $dboReflectionProperty = new ReflectionProperty($database, 'dbo');
        $dboReflectionProperty->setAccessible(true);
        $dboReflectionProperty->setValue($database, new mockDboWithDbErrNo() );

        $dboReflectionMethod = new ReflectionMethod($database, 'startConnection');
        $dboReflectionMethod->setAccessible(true);
        $dboReflectionMethod->invoke($database);
    }

    public function testconstructorSetsUpTheDboAttribute() {
        $database = new Core\Database() ;
        $this->assertObjectHasAttribute("dbo", $database) ;
    }

    public function testconstructorSetsUpTheDboAttributeOfCorrectType() {
        $database = new Core\Database() ;
        $dboReflection = new ReflectionProperty($database, 'dbo');
        $dboReflection->setAccessible(true);
        $this->assertTrue($dboReflection->getValue($database) instanceof \mysqli) ;
    }

    public function testStartConnectionSetsDboWhenCanConnect() {
        $database = new Core\Database() ;
        $dboReflection = new ReflectionProperty($database, 'dbo');
        $dboReflection->setAccessible(true);
        $dboReflection->setValue($database, null);
        $startConnReflection = new ReflectionMethod($database, 'startConnection');
        $startConnReflection->setAccessible(true);
        $startConnReflection->invoke($database);
        $this->assertTrue($dboReflection->getValue($database) != null) ;

    }

    public function testStartConnectionSetsDboToObjectOfCorrectTypeWhenCanConnect() {
        $database = new Core\Database() ;
        $dboReflection = new ReflectionProperty($database, 'dbo');
        $dboReflection->setAccessible(true);
        $dboReflection->setValue($database, null);
        $startConnReflection = new ReflectionMethod($database, 'startConnection');
        $startConnReflection->setAccessible(true);
        $startConnReflection->invoke($database);
        $this->assertTrue($dboReflection->getValue($database) instanceof \mysqli) ;

    }

//
//    /**
//     * @expectedException PHPUnit_Framework_Error_Warning
//     */
//    public function testStartConnectionDoesNotSetDboWhenCanNotConnect() {
//        $database = new Core\Database() ;
//        $dboReflection = new ReflectionProperty($database, 'dbo');
//        $dboReflection->setAccessible(true);
//        $dboReflection->setValue($database, null);
//        $dbUserReflectionProperty = new ReflectionProperty($database, 'dbUser');
//        $dbUserReflectionProperty->setAccessible(true);
//        $dbUserReflectionProperty->setValue($database, "madeUpUser");
//        $startConnReflection = new ReflectionMethod($database, 'startConnection');
//        $startConnReflection->setAccessible(true);
//        $startConnReflection->invoke($database);
//        $this->assertTrue($dboReflection->getValue($database) == null) ;
//    }


}

class mockDboWithDbErrNo {

    public $connect_errno = "1234";

}