# formster

A PHP Form builder and validation library.

# Installation

    composer require chtombleson/formster

# Usage

Here is a simple example of how to use the class. The handle request function will
take any PSR-7 compliant request object.


    <?php
    use Formster\Form;

    $form = new Form();

    $form->addField([
        'name' => 'username',
        'type' => 'text',
        'validate' => ['not_empty'],
    ])
    ->addField([
        'name' => 'password',
        'type' => 'password',
        'validate' => ['not_empty'],
    ])
    ->addField([
        'name' => 'login',
        'type' => 'submit',
        'value' => 'Log In',
    ]);

    if (strtolower($request->getMethod()) == 'post') {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            var_dump($form->getErrors());
        } else {
            var_dump($form->getData());
        }
    } else {
        echo $form->renderForm();
    }

