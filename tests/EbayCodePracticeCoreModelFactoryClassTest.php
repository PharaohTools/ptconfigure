<?php

class EbayCodePracticeCoreModelFactoryClassTest extends PHPUnit_Framework_TestCase {

    private $models;

    public function setUp() {
        require_once("bootstrap.php");
        $this->models = array("Router");
    }

    public function testgetModelReturnsAnObject() {
        $factory = new Core\ModelFactory() ;
        foreach ($this->models as $model) {
            $currentModel = $factory->getModel($model);
            $this->assertTrue( is_object($currentModel) );
        }
    }

    public function testgetModelReturnsAnObjectOfCorrectClass() {
        $factory = new Core\ModelFactory() ;
        foreach ($this->models as $model) {
            $currentModel = $factory->getModel($model);
            $className = 'Model\\'.ucfirst($model);
            $this->assertInstanceOf($className, $currentModel );
        }
    }

}