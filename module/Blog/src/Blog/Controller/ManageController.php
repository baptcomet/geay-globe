<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Zend\View\Model\ViewModel;

class ManageController extends AbstractActionController
{
    public function indexAction()
    {
        if (is_null($this->identity())) {
            return $this->redirect()->toRoute('home');
        }

        $countVisit = $this->getCountVisit();

        $articles = $this->getEntityManager()->getRepository('\Blog\Entity\Article')
            ->findBy(array('status' => array(Article::STATUS_ONLINE, Article::STATUS_ARCHIVED)));

        $this->layout('layout/front');
        return new ViewModel(array(
            'articles' => $articles,
            'countVisit' => $countVisit,
        ));
    }
}