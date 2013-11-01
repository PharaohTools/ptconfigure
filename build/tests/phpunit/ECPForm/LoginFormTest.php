<?php

class EbayCodePracticeECPFormLoginFormClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testSetFormFieldsSetsFormFieldsToArray() {
        $form = new \ECPForm\LoginForm();
        $form->setFormFields();
        $this->assertTrue ( is_array( $form->formFields  ) );
    }

    public function testSetFormFieldsSetsFormFieldsToArrayOfMoreThanZero() {
        $form = new \ECPForm\LoginForm();
        $form->setFormFields();
        $this->assertTrue ( count( $form->formFields  )>0 );
    }

    public function testSetFormFieldsSetsFormFieldsToArrayWithEntriesOfCorrectStructureKeyFieldName() {
        $form = new \ECPForm\LoginForm();
        $form->setFormFields();
        foreach ($form->formFields as $formField) {
            $this->assertTrue ( array_key_exists( 'fieldName', $formField ) ); }
    }

    public function testSetFormFieldsSetsFormFieldsToArrayWithEntriesOfCorrectStructureKeyType() {
        $form = new \ECPForm\LoginForm();
        $form->setFormFields();
        foreach ($form->formFields as $formField) {
            $this->assertTrue ( array_key_exists( 'type', $formField ) ); }
    }

    public function testSetFormFieldsSetsFormFieldsToArrayWithEntriesOfCorrectStructureKeyValidators() {
        $form = new \ECPForm\LoginForm();
        $form->setFormFields();
        foreach ($form->formFields as $formField) {
            $this->assertTrue ( array_key_exists( 'validators', $formField ) ); }
    }

    public function testFormSuccessCallBackReturnsAValue() {
        $form = new \ECPForm\LoginForm();
        $this->assertTrue ( $form->formSuccessCallBack() !== null );
    }

    public function testFormSuccessCallBackReturnsAValueOfBooleanType() {
        $form = new \ECPForm\LoginForm();
        $this->assertTrue ( is_bool($form->formSuccessCallBack()) );
    }

}
