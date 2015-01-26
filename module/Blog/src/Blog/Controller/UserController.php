<?php

namespace Blog\Controller;

use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{

    public function indexAction()
    {
        $writers = $this->getEntityManager()->getRepository('SpecializedGroup\Entity\User')
            ->findBy(array("status" => 1));

        return new ViewModel(array(
            'writers' => $writers,
        ));
    }
}