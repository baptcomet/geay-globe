<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Blog\Entity\Repository\ArticleRepository;
use Blog\Entity\Repository\TagRepository;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    public function indexAction()
    {
        $articles = $this->getEntityManager()->getRepository('\Blog\Entity\Article')
            ->findBy(array('status' => Article::STATUS_ONLINE), array('date' => 'desc'));

        $this->layout('layout/front');
        return new ViewModel(array(
            'articles' => $articles,
        ));
    }

    public function tagsAction()
    {
        $tagsUrl = $this->params()->fromRoute('names');
        if ($tagsUrl === null) {
            // Hack pour la navigation
            return $this->redirect()->toRoute('tags', array('names' => ''));
        }

        if ($tagsUrl != '') {
            $selectedTagNames = explode('+', $tagsUrl);
        } else {
            $selectedTagNames = array();
        }

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getEntityManager()->getRepository('\Blog\Entity\Article');
        /** @var TagRepository $tagRepository */
        $tagRepository = $this->getEntityManager()->getRepository('\Blog\Entity\Tag');

        $articles = $articleRepository->findByTags($selectedTagNames);
        $tags = $tagRepository->findAllActive();

        $this->layout('layout/front');
        return new ViewModel(array(
            'articles' => $articles,
            'tags' => $tags,
            'selectedTagNames' => $selectedTagNames,
        ));
    }

    public function historiqueAction()
    {
        $currentYear = date("Y");
        $year = $this->params()->fromRoute('year', $currentYear);

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getEntityManager()->getRepository('\Blog\Entity\Article');
        $articles = $articleRepository->findBy(
            array(
                'status' => Article::STATUS_ONLINE,
                'year' => $year,
            ),
            array('date' => 'desc')
        );

        $years = $articleRepository->getAllYears();

        $tree = array();
        /** @var Article $article */
        foreach ($articles as $article) {
            $month = $article->getMonth();
            if (!in_array($month, $tree)) {
                $tree[$month] = array();
            }
            //$tree[$month][] = $article->getTitle();
            array_push($tree[$month], $article);
        }
//        debug(sizeof($tree[1])); // TODO find why bug

        $this->layout('layout/front');
        return new ViewModel(array(
            'tree' => $tree,
            'years' => $years,
            'currentYear' => $year,
        ));
    }
}

/*
 * TODO :
 *
 *  1. Entité photo + ManyToOne avec article
 *  3. Liste articles : Titre plus petit et sur le blanc du Polaroid
 *  4. Debug EXIT (logout)
 */
