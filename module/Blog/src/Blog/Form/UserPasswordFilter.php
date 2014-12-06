<?php

namespace Blog\Form;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\Identical;

class UserPasswordFilter extends InputFilter
{

    public function __construct()
    {
        // OLD PASSWORD
        $oldPassword = new Input('oldPassword');
        $oldPassword->setRequired(true)
            ->setAllowEmpty(true);
        $oldPassword->getFilterChain()
            ->attach(new StringTrim());
        $this->add($oldPassword);

        // PASSWORD
        $password = new Input('password');
        $password->setRequired(true);
        $password->getFilterChain()
            ->attach(new StringTrim());
        $password->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(
                new StringLength(
                    array(
                        'min' => 6,
                        'max' => 32
                    )
                )
            );
        $this->add($password);


        // CONFIRMATION
        $validatorIdentical = new Identical(
            array(
                'token' => 'password',
            )
        );
        $validatorIdentical->setMessage('Le Mot de Passe n\'est pas identique', Identical::NOT_SAME);

        $confirmation = new Input('confirmation');
        $confirmation->setRequired(true);
        $confirmation->getFilterChain()
            ->attach(new StringTrim());
        $confirmation->getValidatorChain()
            ->attach($validatorIdentical);
        $this->add($confirmation);
    }
}
