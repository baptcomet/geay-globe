<?php

namespace Blog\Form;

use Blog\Entity\Article;
use Doctrine\ORM\EntityManager;
use Zend\Form\Element\File;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
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

        // Catégorie
        $categorie = new Select('category');
        $categorie->setAttributes(
            array(
                'id' => 'category',
                'class' => 'form-control',
            )
        );
        $categorie->setValueOptions(Article::$categories);
        $categorie->setLabel('Catégorie');
        $categorie->setLabelAttributes(array('class' => 'control-label'));
        $this->add($categorie);

        // Tags
        $tags = new Text('tagsString');
        $tags->setAttributes(
            array(
                'id' => 'tagsString',
                'class' => 'form-control',
            )
        );
        $tags->setLabel('#Tags');
        $tags->setLabelAttributes(array('class' => 'control-label'));
        $this->add($tags);

        // Texte 1
        $text = new Textarea('text1');
        $text->setAttributes(
            array(
                'id' => 'text1',
                'class' => 'form-control',
                'rows' => 5,
                'required' => true,
            )
        );
        $text->setLabel('Texte 1');
        $text->setLabelAttributes(array('class' => 'control-label'));
        $this->add($text);

        // Texte 2
        $text = new Textarea('text2');
        $text->setAttributes(
            array(
                'id' => 'text2',
                'class' => 'form-control',
                'rows' => 5,
            )
        );
        $text->setLabel('Texte 2');
        $text->setLabelAttributes(array('class' => 'control-label'));
        $this->add($text);

        // Texte 3
        $text = new Textarea('text3');
        $text->setAttributes(
            array(
                'id' => 'text3',
                'class' => 'form-control',
                'rows' => 5,
            )
        );
        $text->setLabel('Texte 3');
        $text->setLabelAttributes(array('class' => 'control-label'));
        $this->add($text);

        // Text Position 1
        $photoPosition = new Select('textPosition1');
        $photoPosition->setAttributes(
            array(
                'id' => 'textPosition1',
                'class' => 'form-control',
            )
        );
        $photoPosition->setValueOptions(
            array(
                'left' => 'Gauche',
                'right' => 'Droite',
                'center' => 'Centrée',
            )
        );
        $photoPosition->setLabel('Position Texte 1');
        $photoPosition->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photoPosition);

        // Text Position 2
        $photoPosition = new Select('textPosition2');
        $photoPosition->setAttributes(
            array(
                'id' => 'textPosition2',
                'class' => 'form-control',
            )
        );
        $photoPosition->setValueOptions(
            array(
                'left' => 'Gauche',
                'right' => 'Droite',
                'center' => 'Centrée',
            )
        );
        $photoPosition->setLabel('Position Texte 2');
        $photoPosition->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photoPosition);

        // Text Position 3
        $photoPosition = new Select('textPosition3');
        $photoPosition->setAttributes(
            array(
                'id' => 'textPosition3',
                'class' => 'form-control',
            )
        );
        $photoPosition->setValueOptions(
            array(
                'left' => 'Gauche',
                'right' => 'Droite',
                'center' => 'Centrée',
            )
        );
        $photoPosition->setLabel('Position Texte 3');
        $photoPosition->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photoPosition);

        // Photo 1
        $photo = new File('photofile1');
        $photo->setAttributes(
            array(
                'id' => 'photofile1',
            )
        );
        $photo->setLabel('Photo 1');
        $photo->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photo);

        // Photo 2
        $photo = new File('photofile2');
        $photo->setAttributes(
            array(
                'id' => 'photofile2',
            )
        );
        $photo->setLabel('Photo 2');
        $photo->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photo);

        // Photo Position 1
        $photoPosition = new Select('photoPosition1');
        $photoPosition->setAttributes(
            array(
                'id' => 'photoPosition1',
                'class' => 'form-control',
            )
        );
        $photoPosition->setValueOptions(
            array(
                'left' => 'Gauche',
                'right' => 'Droite',
                'center' => 'Centrée',
            )
        );
        $photoPosition->setLabel('Position Photo 1');
        $photoPosition->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photoPosition);

        // Photo Position 2
        $photoPosition = new Select('photoPosition2');
        $photoPosition->setAttributes(
            array(
                'id' => 'photoPosition2',
                'class' => 'form-control',
            )
        );
        $photoPosition->setValueOptions(
            array(
                'left' => 'Gauche',
                'right' => 'Droite',
                'center' => 'Centrée',
            )
        );
        $photoPosition->setLabel('Position Photo 2');
        $photoPosition->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photoPosition);

        // Submit
        $submit = new Submit('submit');
        $submit->setAttribute('id', 'submit');
        $submit->setValue('Poster');
        $submit->setAttribute('class', 'btn btn-primary');
        $this->add($submit);
    }
}