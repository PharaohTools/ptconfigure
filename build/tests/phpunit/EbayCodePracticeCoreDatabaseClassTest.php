<?php

class EbayCodePracticeCoreDatabaseClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once("bootstrap.php");
    }

    public function testconstructorInitializesObject() {
        $database = new Core\Database() ;
        $this->assertTrue( is_object($database) ) ;
    }

    /**
     * @expectedException Exception
     */
    public function testconstructorThrowsExceptionWhenCantConnectToDb() {
        $database = new Core\Database() ;
        $dboReflectionProperty = new ReflectionProperty($database, 'dbUser');
        $dboReflectionProperty->setAccessible(true);
        $dboReflectionProperty->setValue($database, "madeUpUser");
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


    /**
     * @expectedException Exception
     */
    public function testStartConnectionDoesNotSetDboWhenCanNotConnect() {
        $database = new Core\Database() ;
        $dboReflection = new ReflectionProperty($database, 'dbo');
        $dboReflection->setAccessible(true);
        $dboReflection->setValue($database, null);
        $dbUserReflectionProperty = new ReflectionProperty($database, 'dbUser');
        $dbUserReflectionProperty->setAccessible(true);
        $dbUserReflectionProperty->setValue($database, "madeUpUser");
        $startConnReflection = new ReflectionMethod($database, 'startConnection');
        $startConnReflection->setAccessible(true);
        $startConnReflection->invoke($database);
        $this->assertTrue($dboReflection->getValue($database) == null) ;
    }

}
