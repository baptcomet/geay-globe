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

    public function contactAction()
    {
        $this->layout('layout/front');
        return new ViewModel();
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
        $categoriesUrl = $this->params()->fromRoute('categories');
        if ($categoriesUrl === null) {
            // Hack pour la navigation
            return $this->redirect()->toRoute('histo', array('categories' => ''));
        }

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getEntityManager()->getRepository('\Blog\Entity\Article');

        $allCategories = $articleRepository->getAllCategories();

        if ($categoriesUrl != '') {
            $selectedCategories = explode('+', $categoriesUrl);
        } else {
            $selectedCategories = array();
        }
        foreach ($selectedCategories as $key => $selectedCategory) {
            $selectedCategories[$key] = (int)$selectedCategory;
        }

        $tree = array();
        $years = $articleRepository->getAllYears($selectedCategories);

        foreach ($years as $year) {
            $tree[$year] = array();
            $articles = $articleRepository->findBy(
                array(
                    'status' => Article::STATUS_ONLINE,
                    'year' => $year,
                    'category' => sizeof($selectedCategories) ? $selectedCategories : Article::getCategoryKeys(),
                ),
                array('date' => 'desc')
            );
            /** @var Article $article */
            foreach ($articles as $article) {
                array_push($tree[$year], $article);
            }
        }

        $this->layout('layout/front');
        return new ViewModel(array(
            'tree' => $tree,
            'allCategories' => $allCategories,
            'selectedCategories' => $selectedCategories,
        ));
    }
}

/*
 * TODO :
 *
 *  1. Manage : 1 premier bloc pour gérer les catégories etc
 *  2. Une table 1 line "basics" et 1 form pour changer l'image background/une couleur, et le titre/sous-titre du blog
 *  3. Un slug à chaque article pour l'URL
 *  4. Page contact
 *  5. Naviguer entre articles
 *  6. Suggestions d'articles dans une box "like this"
 */
