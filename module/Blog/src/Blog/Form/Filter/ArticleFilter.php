<?php

namespace Blog\Form\Filter;

use Zend\Filter\File\RenameUpload;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class ArticleFilter extends InputFilter
{
    public function __construct()
    {
        // TITLE
        $title = new Input('title');
        $title->setRequired(true);
        $title->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $title->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength(
                array(
                    'min' => 3,
                    'max' => 50
                )
            ));
        $this->add($title);

        // SUBTITLE
        $subtitle = new Input('subtitle');
        $subtitle->setRequired(false);
        $subtitle->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $subtitle->getValidatorChain()
            ->attach(new StringLength(
                array(
                    'min' => 3,
                    'max' => 50
                )
            ));
        $this->add($subtitle);

        // TEXT
        $text = new Input('text');
        $text->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $text->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength(
                array(
                    'encoding' => 'UTF-8',
                    'min' => 3,
                )
            ));
        $this->add($text);

        // PHOTO
        $photo = new FileInput('photofile');
        $photo->setRequired(false);
        $photo->getValidatorChain()
            ->attach(new UploadFile());
        $photo->getFilterChain()
            ->attach(new RenameUpload(
                array(
                    'target' => './public/img/articles/newphoto',
                    'overwrite' => true,
                    'randomize' => false,
                    'use_upload_name' => false,
                    'use_upload_extension' => true,
                )
            ));
        $this->add($photo);
    }
}