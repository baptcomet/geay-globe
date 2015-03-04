<?php

namespace Blog\Form;

use Blog\Form\Filter\PictureFilter;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Element\File;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class PictureForm extends Form
{
    public function __construct(EntityManager $entityManager = null)
    {
        parent::__construct('project');

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->setInputFilter(new PictureFilter());
        $this->setHydrator(new DoctrineObject($entityManager));

        // PHOTO
        $file = new File('filename');
        $file->setLabel(_('Fichier'))->setLabelAttributes(array('class' => 'control-label'));
        $file->setAttributes(
            array(
                'id' => 'filename',
                'accept' => 'image/*',
                'class' => 'form-contol hide'
            )
        );
        $this->add($file);

        // POSITION PHOTO
        $photoPosition = new Select('position');
        $photoPosition->setAttributes(
            array(
                'id' => 'position',
                'class' => 'form-control',
            )
        );
        $photoPosition->setValueOptions(
            array(
                'center' => 'Centrée',
                'left' => 'Gauche',
                'right' => 'Droite',
            )
        );
        $photoPosition->setLabel('Position Photo');
        $photoPosition->setLabelAttributes(array('class' => 'control-label'));
        $this->add($photoPosition);

        // LEGENDE
        $legend = new Text('legend');
        $legend->setLabel(_('Légende'))
            ->setLabelAttributes(array('class' => 'control-label'))
            ->setAttributes(
                array(
                    'class' => 'form-control',
                )
            );
        $this->add($legend);

        // TEXTE
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

        // POSITION TEXTE
        $textPosition = new Select('textPosition');
        $textPosition->setAttributes(
            array(
                'id' => 'textPosition',
                'class' => 'form-control',
            )
        );
        $textPosition->setValueOptions(
            array(
                'left' => 'Gauche',
                'right' => 'Droite',
                'center' => 'Centrée',
            )
        );
        $textPosition->setLabel('Position Texte');
        $textPosition->setLabelAttributes(array('class' => 'control-label'));
        $this->add($textPosition);

        // SUBMIT
        $submit = new Submit('submit');
        $submit->setValue(_('Ajouter'))
            ->setAttributes(
                array(
                    'class' => 'btn btn-primary',
                )
            );
        $this->add($submit);
    }

    public function hasError()
    {
        if (array_key_exists('filename', $this->getMessages())
            || array_key_exists('position', $this->getMessages())
            || array_key_exists('legend', $this->getMessages())
            || array_key_exists('text', $this->getMessages())
            || array_key_exists('textPosition', $this->getMessages())) {
            return true;
        }
        return false;
    }
}
