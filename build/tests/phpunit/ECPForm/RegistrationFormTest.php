<?php

class EbayCodePracticeECPFormRegistrationFormClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testSetFormFieldsSetsFormFieldsToArray() {
        $form = new \ECPForm\RegistrationForm();
        $form->setFormFields();
        $this->assertTrue ( is_array( $form->formFields  ) );
    }

    public function testSetFormFieldsSetsFormFieldsToArrayOfMoreThanZero() {
        $form = new \ECPForm\RegistrationForm();
        $form->setFormFields();
        $this->assertTrue ( count( $form->formFields  )>0 );
    }

    public function testSetFormFieldsSetsFormFieldsToArrayWithEntriesOfCorrectStructureKeyFieldName() {
        $form = new \ECPForm\RegistrationForm();
        $form->setFormFields();
        $this->assertTrue ( array_key_exists( 'fieldName', $form->formFields[0] ) );
    }

    public function testSetFormFieldsSetsFormFieldsToArrayWithEntriesOfCorrectStructureKeyType() {
        $form = new \ECPForm\RegistrationForm();
        $form->setFormFields();
        $this->assertTrue ( array_key_exists( 'type', $form->formFields[0] ) );
    }

    public function testSetFormFieldsSetsFormFieldsToArrayWithEntriesOfCorrectStructureKeyValidators() {
        $form = new \ECPForm\RegistrationForm();
        $form->setFormFields();
        $this->assertTrue ( array_key_exists( 'validators', $form->formFields[0] ) );
    }

    public function testFormSuccessCallBackReturnsAValue() {
        $form = new \ECPForm\RegistrationForm();
        $this->assertTrue ( $form->formSuccessCallBack() !== null );
    }

    public function testFormSuccessCallBackReturnsAValueOfBooleanType() {
        $form = new \ECPForm\RegistrationForm();
        $this->assertTrue ( is_bool($form->formSuccessCallBack()) );
    }

}
