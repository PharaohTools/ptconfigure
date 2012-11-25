<?php


class EbayCodePracticeModelFactoryClassTest extends PHPUnit_Framework_TestCase {

    private $factory;
    private $models;

    public function setUp() {
        require_once("../src/bootstrap.php");
        $this->factory = new EbayCodePracticeModelFactoryClass() ;
        $this->models = array("Router");
    }

    public function testgetModelReturnsAnObject() {
        foreach ($this->models as $model) {
            $currentModel = $this->factory->getModel($model);
            $this->assertTrue( is_object($currentModel) );
        }
    }

    public function testgetModelReturnsAnObjectOfCorrectClass() {
        foreach ($this->models as $model) {
            $currentModel = $this->factory->getModel($model);
            $className = "EbayCodePractice".$model."Model";
            $this->assertTrue( $currentModel instanceof $className );
        }
    }

}
