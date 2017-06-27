<?php

namespace Blog\Form;

use Blog\Entity\Article;
use Blog\Model\Calendar;
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

        // Jour
        $joursOptions = array();
        foreach (range(1, 9) as $d) {
            $day = '0'.$d;
            $joursOptions[$day] = $day;
        }
        foreach (range(10, 31) as $day) {
            $joursOptions[$day] = $day;
        }
        $jour = new Select('day');
        $jour->setAttributes(
            array(
                'id' => 'day',
                'class' => 'form-control',
            )
        );
        $jour->setValueOptions($joursOptions);
        $jour->setLabel('Jour');
        $jour->setLabelAttributes(array('class' => 'control-label'));
        $this->add($jour);

        // Mois
        $mois = new Select('month');
        $mois->setAttributes(
            array(
                'id' => 'month',
                'class' => 'form-control',
            )
        );
        $mois->setValueOptions(Calendar::getStaticMonthNames());
        $mois->setLabel('Mois');
        $mois->setLabelAttributes(array('class' => 'control-label'));
        $this->add($mois);

        // Annee
        $anneesOptions = array();
        foreach (range(date('Y'), 2000) as $year) {
            $anneesOptions[$year] = $year;
        }
        $annee = new Select('year');
        $annee->setAttributes(
            array(
                'id' => 'year',
                'class' => 'form-control',
            )
        );
        $annee->setValueOptions($anneesOptions);
        $annee->setLabel('Année');
        $annee->setLabelAttributes(array('class' => 'control-label'));
        $this->add($annee);

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