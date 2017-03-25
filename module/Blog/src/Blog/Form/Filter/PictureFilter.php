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
        // FLICKR URL
        $flickrUrl = new Input('flickrUrl');
        $flickrUrl->setRequired(false)
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $flickrUrl->getValidatorChain()
            ->attach(new NotEmpty());
        $this->add($flickrUrl);

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
    }
}
