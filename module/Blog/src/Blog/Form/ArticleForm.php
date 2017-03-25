<?php

namespace Blog\Form;

use Blog\Entity\Article;
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

        // Présentation
        $presentation = new Select('presentation');
        $presentation->setAttributes(
            array(
                'id' => 'presentation',
                'class' => 'form-control',
            )
        );
        $presentation->setValueOptions(Article::$presentations);
        $presentation->setLabel('Présentation');
        $presentation->setLabelAttributes(array('class' => 'control-label'));
        $this->add($presentation);

        // Mosaique
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

        //TODO add champ date

        // Youtube
        $youtube = new Text('youtube');
        $youtube->setAttributes(
            array(
                'id' => 'youtube',
                'class' => 'form-control',
            )
        );
        $youtube->setLabel('Vidéo YouTube (copier après ...watch?v=)');
        $youtube->setLabelAttributes(array('class' => 'control-label'));
        $this->add($youtube);
        
        // Texte
        $text = new Textarea('text');
        $text->setAttributes(
            array(
                'id' => 'text',
                'class' => 'form-control',
                'rows' => 5,
            )
        );
        $text->setLabel('Texte');
        $text->setLabelAttributes(array('class' => 'control-label'));
        $this->add($text);
        
        // Photos
        $photos = new Textarea('photos');
        $photos->setAttributes(
            array(
                'id' => 'photos',
                'class' => 'form-control',
                'rows' => 5,
            )
        );
        $photos->setLabel('Précharger des photos (URL Flickr)');
        $photos->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photos);

        // Submit
        $submit = new Submit('submit');
        $submit->setAttribute('id', 'submit');
        $submit->setValue('Enregistrer');
        $submit->setAttribute('class', 'btn btn-primary');
        $this->add($submit);
    }
}