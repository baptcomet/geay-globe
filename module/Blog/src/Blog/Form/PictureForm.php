<?php

namespace Blog\Form;

use Blog\Form\Filter\PictureFilter;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class PictureForm extends Form
{
    public function __construct(EntityManager $entityManager = null)
    {
        parent::__construct('picture');

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->setInputFilter(new PictureFilter());
        $this->setHydrator(new DoctrineObject($entityManager));

        // FLICKR URL
        $flickerUrl = new Text('flickrUrl');
        $flickerUrl->setLabel(_('URL Flickr'))->setLabelAttributes(array('class' => 'control-label'));
        $flickerUrl->setAttributes(
            array(
                'class' => 'form-control'
            )
        );
        $this->add($flickerUrl);

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
        if (array_key_exists('flickrUrl', $this->getMessages())
            || array_key_exists('legend', $this->getMessages())) {
            return true;
        }
        return false;
    }
}
