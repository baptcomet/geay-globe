<?php

namespace Blog\Form;

use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class AuthForm extends Form
{
    public function __construct($name = null, $objectManager = null)
    {
        parent::__construct('auth');
        $this->setAttribute('method', 'post');
        $this->setAttributes(
            array(
                'id' => 'auth',
                'class' => 'form-horizontal',
                'role' => 'form'
            )
        );

        // Nickname
        $nickname = new Text('nickname');
        $nickname->setLabel('Pseudo');
        $nickname->setLabelAttributes(array('class' => 'col-md-4 control-label text-white'));
        $nickname->setAttributes(
            array(
                'id' =>'nickname',
                'class' => 'form-control',
                'placeholder' => 'Votre Pseudonyme',
            )
        );
        $this->add($nickname);

        // Password
        $password = new Password('password');
        $password->setLabel('Mot de Passe');
        $password->setLabelAttributes(array('class' => 'col-md-4 control-label text-white'));
        $password->setAttributes(
            array(
                'id' => 'password',
                'class' => 'form-control',
                'placeholder' => 'Mot de Passe',
            )
        );
        $this->add($password);

        // Submit button
        $submit = new Submit('submit');
        $submit->setValue('Connexion');
        $submit->setAttributes(
            array(
                'id' => 'submitbutton',
                'class' => 'btn btn-default btn-lg btn-block'
            )
        );
        $this->add($submit);
    }
}
