<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Zend\View\Model\ViewModel;

class ManageController extends AbstractActionController
{

    public function indexAction()
    {
        $articles = $this->getEntityManager()->getRepository('\Blog\Entity\Article')
            ->findBy(array('status' => Article::STATUS_ONLINE));

        $this->layout('layout/front');
        return new ViewModel(array(
            'articles' => $articles,
        ));
    }
}