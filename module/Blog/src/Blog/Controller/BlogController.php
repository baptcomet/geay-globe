<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
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

    public function tagsAction()
    {
        $articles = new ArrayCollection();

        $tags = $this->getEntityManager()->getRepository('\Blog\Entity\Tag')->findAll();

        $this->layout('layout/front');
        return new ViewModel(array(
            'articles' => $articles,
            'tags' => $tags,
        ));
    }

    public function ajaxLoadArticlesAction()
    {
        $articles = new ArrayCollection();

        $tags = $this->getEntityManager()->getRepository('\Blog\Entity\Tag')->findAll();

        // TODO Ajax call with tags in params, retrieve articles and return no viewmodel but the list partial

        $this->layout('layout/front');
        return new ViewModel(array(
            'articles' => $articles,
            'tags' => $tags,
        ));
    }
}