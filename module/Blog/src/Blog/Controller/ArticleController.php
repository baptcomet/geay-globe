<?php

namespace Blog\Controller;

use Zend\View\Model\ViewModel;

class OfferController extends AbstractActionController
{
    public function detailAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('offer');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $article = $objectManager->getRepository('Blog\Entity\Article')->find($id);

        $this->layout('layout/front');
        return new ViewModel(array(
            'article' => $article,
        ));
    }
}