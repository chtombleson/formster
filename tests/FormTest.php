<?php

use Formster\Form;

class FormTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $form = new Form();

        $this->assertInstanceOf('Formster\Form', $form);
    }

    public function testForm()
    {
        $form = new Form('get', 'index.php', 'multipart/form-data', 'form');

        $this->assertEquals($form->get(), [
            'action' => 'index.php',
            'method' => 'get',
            'enctype' => 'multipart/form-data',
            'id' => 'form',
            'fields' => [],
            'validation' => [],
        ]);

        $form->set([
            'action' => 'contact.php',
            'method' => 'post',
            'enctype' => 'text/plain',
            'id' => 'contact-form',
        ]);

        $this->assertEquals($form->get(), [
            'action' => 'contact.php',
            'method' => 'post',
            'enctype' => 'text/plain',
            'id' => 'contact-form',
            'fields' => [],
            'validation' => [],
        ]);
    }

    public function testValidate()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'text',
        ]);

        $form->addValidation('name', [
            'not_empty',
        ]);

        $request = new MockServerRequest();
        $request->post = ['name' => ''];
        $form->handleRequest($request);

        $this->assertFalse($form->isValid());

        $request->post = ['name' => 'hello world'];
        $form->handleRequest($request);

        $this->assertTrue($form->isValid());

        $form = new Form();
        $form->addField([
            'name' => 'name',
            'type' => 'text',
            'validate' => ['not_empty']
        ])
        ->addField([
            'name' => 'email',
            'type' => 'email',
            'validate' => ['not_empty', 'email'],
        ])
        ->addField([
            'name' => 'password',
            'type' => 'password',
            'validate' => ['not_empty'],
        ]);

        $request->post = ['name' => '', 'email' => 'jimbobo.local', 'password' => 'eruiere'];
        $form->handleRequest($request);

        $this->assertFalse($form->isValid());
        $this->assertEquals($form->getErrors(), [
            'name' => [
                'is_empty',
            ],
            'email' => [
                'email_is_not_valid',
            ],
        ]);

        $form = new Form();
        $form->addField([
            'name' => 'name',
            'type' => 'text',
            'validate' => ['not_empty']
        ])
        ->addField([
            'name' => 'email',
            'type' => 'email',
            'validate' => ['not_empty', 'email'],
        ])
        ->addField([
            'name' => 'password',
            'type' => 'password',
            'validate' => ['not_empty'],
        ]);

        $request->post = ['name' => 'hi', 'email' => 'jimbob@local.loc', 'password' => 'eruiere'];
        $form->handleRequest($request);

        $this->assertTrue($form->isValid());
        $this->assertEquals($form->getErrors(), []);
        $this->assertEquals($form->getData(), [
            'name' => 'hi',
            'email' => 'jimbob@local.loc',
            'password' => 'eruiere',
        ]);
    }

    public function testFormAddTextField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'text',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\TextField', $field);
    }

    public function testFormAddTextareaField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'textarea',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\TextareaField', $field);
    }

    public function testFormAddButtonField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'button',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\ButtonField', $field);
    }

    public function testFormAddCheckboxField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'checkbox',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\CheckboxField', $field);
    }

    public function testFormAddEmailField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'email',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\EmailField', $field);
    }

    public function testFormAddPasswordField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'password',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\PasswordField', $field);
    }

    public function testFormAddRadioField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'radio',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\RadioField', $field);
    }

    public function testFormAddSearchField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'search',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\SearchField', $field);
    }

    public function testFormAddSelectField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'select',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\SelectField', $field);
    }

    public function testFormAddSubmitField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'submit',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\SubmitField', $field);
    }


    public function testFormAddTelField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'tel',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\TelField', $field);
    }

    public function testFormAddUrlField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'url',
        ]);

        $field = $form->getField('name');

        $this->assertInstanceOf('\Formster\Fields\UrlField', $field);
    }

    public function testFormRemoveField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'url',
        ]);

        $form->removeField('name');
        $field = $form->getField('name');

        $this->assertEquals($field, null);
    }

    public function testRenderFormOpen()
    {
        $form = new Form('get', 'index.php', 'multipart/form-data', 'form');

        $this->assertEquals(
            $form->renderFormOpen(),
            '<form method="get" action="index.php" enctype="multipart/form-data" id="form">'
        );
    }

    public function testRenderFormClose()
    {
        $form = new Form();

        $this->assertEquals(
            $form->renderFormClose(),
            '</form>'
        );
    }

    public function testRenderField()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'text',
        ]);

        $this->assertEquals(
            $form->renderField('name'),
            '<input type="text" name="name">'
        );
    }

    public function testRenderForm()
    {
        $form = new Form();

        $form->addField([
            'name' => 'name',
            'type' => 'text',
        ]);

        $this->assertEquals(
            $form->renderForm(),
            '<form method="post"><input type="text" name="name"></form>'
        );
    }
}

