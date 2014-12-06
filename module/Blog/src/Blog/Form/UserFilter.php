<?php
namespace Blog\Form;

use Doctrine\ORM\EntityManager;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\Callback;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class UserFilter extends InputFilter
{
    /**
     * @param EntityManager $entityManager
     * @param bool $mustValidateCampaign
     */
    public function __construct($entityManager = null, $mustValidateCampaign = false)
    {
        // LASTNAME
        $lastName = new Input('lastName');
        $lastName->setRequired(true);
        $lastName->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $lastName->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength(
                array(
                    'min' => 1,
                    'max' => 100
                )
            ));
        $this->add($lastName);

        // FIRSTNAME
        $firstName = new Input('firstName');
        $firstName->setRequired(true);
        $firstName->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $firstName->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength(
                array(
                    'min' => 1,
                    'max' => 100
                )
            ));
        $this->add($firstName);

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

        // EMAIL
        $callbackUserEmailNotExist = new Callback(
            array(
                'callback' => array('Blog\Form\UserFilter', 'userEmailNotExist'),
                'callbackOptions' => array('entityManager' => $entityManager),
                'messages' => array(Callback::INVALID_VALUE => 'L\'email choisi est déjà présent dans notre base de données.'),
            )
        );
        $email = new Input('email');
        $email->setRequired(true);
        $email->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $email->getValidatorChain()
            ->attach(new StringLength(array('max' => 128)))
            ->attach(new EmailAddress())
            ->attach($callbackUserEmailNotExist);
        $this->add($email);
    }

    /**
     * Vérifie si le pseudo existe déjà dans la base de données
     * @param $nickname
     * @param $context
     * @param EntityManager $entityManager
     * @return bool
     */
    public static function userNicknameNotExist($nickname, $context, $entityManager)
    {
        $return = true;

        /** @var UserRepository $repository */
        $repository = $entityManager->getRepository('Blog\Entity\User');
        $user_id = $repository->getIdByNickname($nickname);


        if ($user_id && isset($context['id'])) {
            if ($user_id != $context['id']) {
                $return = false;
            }
        } else {
            if ($user_id) {
                $return = false;
            }
        }

        return $return;
    }
}
