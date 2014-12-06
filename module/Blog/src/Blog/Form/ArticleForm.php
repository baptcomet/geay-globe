<?php

namespace Blog\Form;

use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ArticleForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('article');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        // Identifiant
        $id = new Hidden('id');
        $this->add($id);

        // Titre
        $title = new Text('title');
        $title->setAttribute('id', 'title');
        $title->setLabel('Titre');
        $title->setAttribute('class', 'form-control');
        $title->setLabelAttributes(array('class' => 'control-label'));
        $this->add($title);

        // Submit
        $submit = new Submit('submit');
        $submit->setAttribute('id', 'submit');
        $submit->setValue('Poster');
        $submit->setAttribute('class', 'btn btn-primary');
        $this->add($submit);

        // Filtres
        $this->addInputFilter();
    }


    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        $inputFilter->add(
            $factory->createInput(
                array(
                    'name' => 'id',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Int'),
                    ),
                )
            )
        );

        $inputFilter->add(
            $factory->createInput(
                array(
                    'name' => 'title',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 150,
                            ),
                        ),
                    ),
                )
            )
        );

        $this->setInputFilter($inputFilter);
    }
}