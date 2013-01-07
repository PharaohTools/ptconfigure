<?php

class EbayCodePracticeECPFormBaseFormClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testObjectWillInstantiate() {
        $formObject = new \ECPForm\BaseForm();
        $this->assertTrue ( is_object($formObject) );
    }

    public function testConstructorSetsDboProperty() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'dbo');
        $this->assertTrue ( $propertyReflector->getValue($formObject) != null );
    }

    public function testConstructorSetsDboPropertyOfCorrectType() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'dbo');
        $this->assertTrue ( is_object($propertyReflector->getValue($formObject)) );
    }

    public function testConstructorSetsDboPropertyOfCorrectClass() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'dbo');
        $this->assertTrue ( $propertyReflector->getValue($formObject) instanceof \Core\Database );
    }

    public function testConstructorSetsDbHelpersProperty() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'dbHelpers');
        $this->assertTrue ( $propertyReflector->getValue($formObject) != null );
    }

    public function testConstructorSetsDbHelpersPropertyOfCorrectType() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'dbHelpers');
        $this->assertTrue ( is_object($propertyReflector->getValue($formObject)) );
    }

    public function testConstructorSetsDbHelpersPropertyOfCorrectClass() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'dbHelpers');
        $this->assertTrue ( $propertyReflector->getValue($formObject) instanceof \Core\DatabaseHelpers );
    }

    public function testConstructorSetsValidationHelpersProperty() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'validationHelpers');
        $this->assertTrue ( $propertyReflector->getValue($formObject) != null );
    }

    public function testConstructorSetsValidationHelpersPropertyOfCorrectType() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'validationHelpers');
        $this->assertTrue ( is_object($propertyReflector->getValue($formObject)) );
    }

    public function testConstructorSetsValidationHelpersPropertyOfCorrectClass() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'validationHelpers');
        $this->assertTrue ( $propertyReflector->getValue($formObject) instanceof \Core\ValidationHelpers );
    }

    public function testConstructorSetsValidationResultProperty() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'validationResult');
        $this->assertTrue ( $propertyReflector->getValue($formObject) != null );
    }

    public function testConstructorSetsValidationResultPropertyOfCorrectType() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'validationResult');
        $this->assertTrue ( is_array($propertyReflector->getValue($formObject)) );
    }

    public function testConstructorSetFormFieldsPropertyOfCorrectType() {
        $formObject = new \ECPForm\BaseForm();
        $propertyReflector = new \ReflectionProperty($formObject, 'formFields');
        $this->assertTrue ( is_array($propertyReflector->getValue($formObject)) );
    }

    public function testBindFormSetsFormFields() {
        $formObject = new \ECPForm\BaseForm();
        $formObject->bindForm();
        $this->assertTrue ( is_array($formObject->formFields) );
    }

    public function testBindFormSetsFormRequest() {
        $formObject = new \ECPForm\BaseForm();
        $formObject->bindForm();
        $this->assertTrue ( is_array($formObject->formRequest) );
    }

    public function testBindFormSetsValidationResult() {
        $formObject = new \ECPForm\BaseForm();
        $formObject->bindForm();
        $this->assertTrue ( is_array($formObject->validationResult) );
    }

    public function testGetValidationResultReturnsArray() {
        $formObject = new \ECPForm\BaseForm();
        $this->assertTrue ( is_array($formObject->getValidationResult()) );
    }

    public function testGetValidationResultReturnsArrayWithResultsKey() {
        $formObject = new \ECPForm\BaseForm();
        $testRay = $formObject->getValidationResult();
        $this->assertTrue ( array_key_exists("results", $testRay) );
    }

    public function testGetValidationResultReturnsArrayWithErrorsKey() {
        $formObject = new \ECPForm\BaseForm();
        $testRay = $formObject->getValidationResult();
        $this->assertTrue ( array_key_exists("errors", $testRay) );
    }

    public function testGetValidationResultReturnsArrayWithMessagesKey() {
        $formObject = new \ECPForm\BaseForm();
        $testRay = $formObject->getValidationResult();
        $this->assertTrue ( array_key_exists("messages", $testRay) );
    }

    public function testValidateFormReturnsArray() {
        $formObject = new \ECPForm\BaseForm();
        $this->assertTrue ( is_array($formObject->validateForm()) );
    }

    public function testValidateFormReturnsArrayWithResultsKey() {
        $formObject = new \ECPForm\BaseForm();
        $testRay = $formObject->validateForm();
        $this->assertTrue ( array_key_exists("results", $testRay) );
    }

    public function testValidateFormReturnsArrayWithErrorsKey() {
        $formObject = new \ECPForm\BaseForm();
        $testRay = $formObject->getValidationResult();
        $this->assertTrue ( array_key_exists("errors", $testRay) );
    }

    public function testValidateFormReturnsArrayWithMessagesKey() {
        $formObject = new \ECPForm\BaseForm();
        $testRay = $formObject->getValidationResult();
        $this->assertTrue ( array_key_exists("messages", $testRay) );
    }

}
