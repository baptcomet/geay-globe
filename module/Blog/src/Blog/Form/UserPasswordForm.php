<?php

namespace Blog\Form;

use Zend\Form\Form;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;

class UserPasswordForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('password');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->setInputFilter(new UserPasswordFilter());

        // OLD PASSWORD
        $oldPassword = new Password('oldPassword');
        $oldPassword->setLabel('Mot de Passe Actuel')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'oldPassword',
                    'class' => 'form-control',
                )
            );
        $this->add($oldPassword);

        // PASSWORD
        $password = new Password('password');
        $password->setLabel('Nouveau Mot de Passe')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'password',
                    'class' => 'form-control',
                    'required' => true
                )
            );
        $this->add($password);

        // CONFIRMATION
        $confirmation = new Password('confirmation');
        $confirmation->setLabel('Confirmation Nouveau Mot de Passe')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'confirmation',
                    'class' => 'form-control',
                    'required' => true
                )
            );
        $this->add($confirmation);

        // SUBMIT
        $submit = new Submit('submit');
        $submit->setValue('Changer')
            ->setAttributes(
                array(
                    'id' => 'save_password',
                    'class' => 'btn btn-success',
                )
            );
        $this->add($submit);
    }
}
