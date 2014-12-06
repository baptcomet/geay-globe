<?php

namespace Blog\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Http\Response as HttpResponse;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;
use Zend\Mvc\Exception;

/**
 * Basic action controller
 */
abstract class AbstractActionController extends ZendAbstractActionController
{

    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    /**
     * @return Response
     */
    protected function unauthorizedAction()
    {
        $this->flashMessenger()->addWarningMessage('Vous n\'êtes pas autorisé à accéder à cette rubrique.');
        return $this->redirect()->toRoute('blog');
    }
}