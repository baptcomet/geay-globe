<?php

namespace Blog\Form;

use Blog\Form\Filter\ArticleFilter;
use Zend\Form\Element\File;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

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
        $title->setAttributes(
            array(
                'id' => 'title',
                'class' => 'form-control',
                'required' => true,
            )
        );
        $title->setLabel('Titre');
        $title->setLabelAttributes(array('class' => 'control-label'));
        $this->add($title);

        // Sous-Titre
        $subtitle = new Text('subtitle');
        $subtitle->setAttributes(
            array(
                'id' => 'subtitle',
                'class' => 'form-control',
            )
        );
        $subtitle->setLabel('Sous-Titre');
        $subtitle->setLabelAttributes(array('class' => 'control-label'));
        $this->add($subtitle);

        // Texte
        $text = new Textarea('text');
        $text->setAttributes(
            array(
                'id' => 'text',
                'class' => 'form-control',
                'rows' => 5,
                'required' => true,
            )
        );
        $text->setLabel('Texte');
        $text->setLabelAttributes(array('class' => 'control-label'));
        $this->add($text);

        // Photo
        $photo = new File('photofile');
        $photo->setAttributes(
            array(
                'id' => 'photofile',
            )
        );
        $photo->setLabel('Photo');
        $photo->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photo);

        // Submit
        $submit = new Submit('submit');
        $submit->setAttribute('id', 'submit');
        $submit->setValue('Poster');
        $submit->setAttribute('class', 'btn btn-primary');
        $this->add($submit);
    }
}