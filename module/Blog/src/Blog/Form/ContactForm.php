<?php

namespace Blog\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

class ContactForm extends Form
{
    public function __construct($name = null, $objectManager = null)
    {
        // we want to ignore the name passed
        parent::__construct('contact');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');
        $this->addInputFilter();

        // Prénom input
        $firstname = new Element\Text('firstname');
        $firstname->setLabel('Prénom')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'firstname',
                    'class' => 'form-control',
                )
            );
        $this->add($firstname);

        // Nom input
        $lastname = new Element\Text('lastname');
        $lastname->setLabel('Nom')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'lastname',
                    'class' => 'form-control',
                )
            );
        $this->add($lastname);

        // Email input
        $email = new Element\Text('email');
        $email->setLabel('Adresse Email')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'email',
                    'class' => 'form-control',
                )
            );
        $this->add($email);

        // Sujet input
        $subject = new Element\Text('subject');
        $subject->setLabel('Sujet')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'subject',
                    'class' => 'form-control',
                )
            );
        $this->add($subject);

        // Message input
        $message = new Element\Textarea('message');
        $message->setLabel('Message')
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'id' => 'message',
                    'class' => 'form-control',
                    'rows' => 6,
                    'cols' => 75
                )
            );
        $this->add($message);


        // Submit button
        $submit = new Element\Submit('send_mail');
        $submit->setValue('Envoyer')
            ->setAttributes(
                array(
                    'class' => 'btn btn-success'
                )
            );
        $this->add($submit);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();

        // FIRSTNAME
        $inputFilter->add(
            array(
                'name' => 'firstname',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array('name' => 'NotEmpty'),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )
        );

        // LASTNAME
        $inputFilter->add(
            array(
                'name' => 'lastname',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array('name' => 'NotEmpty'),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )
        );

        // EMAIL
        $inputFilter->add(
            array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array('name' => 'NotEmpty'),
                    array('name' => 'EmailAddress'),
                ),
            )
        );

        // SUBJECT_ID
        $inputFilter->add(
            array(
                'name' => 'subject',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array('name' => 'NotEmpty'),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )
        );

        // MESSAGE
        $inputFilter->add(
            array(
                'name' => 'message',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array('name' => 'NotEmpty'),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 500,
                        ),
                    ),
                ),
            )
        );

        $this->setInputFilter($inputFilter);
    }
}