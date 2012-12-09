<?php

class EbayCodePracticeCoreDatabaseClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once("bootstrap.php");
    }

    public function testconstructorInitializesObject() {
        $database = new Core\Database() ;
        $this->assertTrue( is_object($database) ) ;
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

    public function testdoQueryExecutesAQuery() {

        $mockDatabaseObject = $this->getMockBuilder('\mysqli')
            ->disableOriginalConstructor()
            ->getMock();
        $mockDatabaseObject->expects($this->once())
            ->method('query');

        $database = new Core\Database() ;
        $dboReflection = new ReflectionProperty($database, 'dbo');
        $dboReflection->setAccessible(true);
        $dboReflection->setValue($database, $mockDatabaseObject);

        $database->doQuery("SELECT * FROM users");
    }

    public function testdoQueryReturnsAValue() {
        $database = new Core\Database() ;
        $returnedValue = $database->doQuery("SELECT * FROM users");
        $this->assertTrue ( isset($returnedValue) );
    }

    public function testdoQueryReturnsABooleanValue() {
        $database = new Core\Database() ;
        $returnedValue = $database->doQuery("SELECT * FROM users");
        $this->assertTrue ( in_array($returnedValue, array(true, false) ) );
    }

}
