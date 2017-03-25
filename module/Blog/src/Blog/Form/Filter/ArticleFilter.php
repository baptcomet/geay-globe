<?php

namespace Blog\Form\Filter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
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
        $text->setRequired(false);
        $text->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $this->add($text);
    }
}