<?php

namespace Blog\Form\Filter;

use Blog\Entity\Picture;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class PictureFilter extends InputFilter
{
    public function __construct()
    {
        // FILE
        $file = new Input('filename');
        $file->setRequired(false)
            ->getFilterChain();
        $file->getValidatorChain()
            ->attach(new Extension(Picture::getStaticAuthorisedExtensionList()))
            ->attach(
                new Size(
                    array(
                        'max' => '8MB'
                    )
                )
            )
            ->attach(new UploadFile());
        $this->add($file);

        // LEGEND
        $legend = new Input('legend');
        $legend->setRequired(false)
            ->getFilterChain()
                ->attach(new StringTrim())
                ->attach(new StripTags());
        $legend->getValidatorChain()
            ->attach(
                new StringLength(
                    array(
                        'min' => 1,
                        'max' => 256,
                    )
                )
            );
        $this->add($legend);

        // TEXTE
        $text = new Input('text');
        $text->setRequired(false)
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $text->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength(
                array(
                    'encoding' => 'UTF-8',
                    'min' => 1,
                )
            ));
        $this->add($text);
    }
}
