<?php
namespace Blog\Form;

use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class UserForm extends Form
{

    public function __construct($name = null, $objectManager = null, $isAdmin = false, $mustValidateCampaign = false)
    {
        // we want to ignore the name passed
        parent::__construct('user');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->setInputFilter(new UserFilter($objectManager, $mustValidateCampaign));

        // ID
        $id = new Hidden('id');
        $this->add($id);

        // FIRSTNAME
        $firstname = new Text('firstName');
        $firstname->setLabel('Prénom')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'firstName',
                    'class' => 'form-control',
                    'required' => true
                )
            );
        $this->add($firstname);

        // LASTNAME
        $lastname = new Text('lastName');
        $lastname->setLabel('Nom')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'lastName',
                    'class' => 'form-control',
                    'required' => true
                )
            );
        $this->add($lastname);

        // EMAIL
        $email = new Text('email');
        $email->setLabel('Email')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'email',
                    'class' => 'form-control',
                    'required' => true
                )
            );
        $this->add($email);

        // PASSWORD
        $password = new Password('password');
        $password->setLabel('Password')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'password',
                    'class' => 'form-control',
                    'required' => true
                )
            );
        $this->add($password);

        // SUBMIT
        $submit = new Submit('submit');
        $submit->setValue('Enregistrer')
            ->setAttributes(
                array(
                    'id' => 'save_user',
                    'class' => 'btn btn-success',
                )
            );
        $this->add($submit);
    }
}
