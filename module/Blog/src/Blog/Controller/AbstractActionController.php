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
        return $this->redirect()->toRoute('home');
    }

    protected function countVisit()
    {
        $counter_name = "./data/counterVisit.txt";

        // Check if a text file exists. If not create one and initialize it to zero.
        if (!file_exists($counter_name)) {
            $f = fopen($counter_name, "w");
            fwrite($f, "1");
            fclose($f);
        }

        // Has visitor been counted in this session?
        // If not, increase counter value by one
        if(!isset($_SESSION['hasVisited'])){
            $_SESSION['hasVisited']="yes";

            // Read the current value of our counter file
            $f = fopen($counter_name,"wr");
            $counterVal = (int) fread($f, filesize($counter_name));
            $counterVal++;
            fwrite($f, $counterVal);
            fclose($f);
        }
    }

    protected function getCountVisit()
    {
        $counter_name = "./data/counter.txt";
        $counterVal = 0;

        // Check if a text file exists. If not create one and initialize it to zero.
        if (file_exists($counter_name)) {
            $f = fopen($counter_name,"w");
            $counterVal = fread($f, filesize($counter_name));
            fclose($f);
        }

        return $counterVal;
    }
}