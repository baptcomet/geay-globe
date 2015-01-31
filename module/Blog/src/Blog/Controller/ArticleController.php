<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Blog\Entity\Tag;
use Blog\Form\ArticleForm;
use Blog\Form\Filter\ArticleFilter;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

class ArticleController extends AbstractActionController
{
    public function detailAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('blog');
        }

        $entityManager = $this->getEntityManager();
        $article = $entityManager->getRepository('Blog\Entity\Article')->find($id);

        $this->layout('layout/front');
        return new ViewModel(array(
            'article' => $article,
        ));
    }

    public function addAction()
    {
        $entityManager = $this->getEntityManager();

        $form = new ArticleForm();
        $form->setHydrator(new DoctrineHydrator($entityManager));

        $article = new Article();
        $form->bind($article);

        $tags = $entityManager->getRepository('Blog\Entity\Tag')->findAll();
        $allTags = array();
        /** @var Tag $tag */
        foreach ($tags as $tag) {
            array_push($allTags, $tag->getTitle());
        }
        $autocompleteTagSource = json_encode($allTags);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);

            if ($form->isValid()) {
                $article->setWriter($this->identity());
                $entityManager->persist($article);
                $entityManager->flush();

                // Traite file si on en a un todo fix bug
                if ($data['photofile']['name'] != '') {
                    $extension = pathinfo($data['photofile']['name'], PATHINFO_EXTENSION);
                    $oldfilename = Article::PHOTO_FOLDER . 'newphoto.' . $extension;
                    $newfilename = Article::PHOTO_FOLDER . $article->getId() . '.' . $extension;

                    rename($oldfilename, $newfilename);
                    create_square_image($newfilename, Article::PHOTO_FOLDER . 'thumbnail' .$article->getId() . '.' . $extension, 50);

                    $article->setPhoto($article->getId() . '.' . $extension);
                    $article->setThumbnail('thumbnail' . $article->getId() . '.' . $extension);
                }
                // Traite les Tags
                if ($data['tagsString'] != '') {
                    $tags = explode(' ', $data['tagsString']);
                    $tagsObjects = new ArrayCollection();
                    foreach ($tags as $tag) {
                        /** @var Tag $existingTag */
                        $existingTag = $entityManager->getRepository('BLog\Entity\Tag')
                            ->findOneBy(array('title' => $tag));
                        if (!is_null($existingTag)) {
                            $tagsObjects->add($existingTag);
                        } else {
                            $tagObject = new Tag();
                            $tagObject->setTitle($tag);
                            $tagsObjects->add($tagObject);
                        }
                    }
                    $article->setTags($tagsObjects);
                }
                $entityManager->flush();

                $this->flashMessenger()->addSuccessMessage(
                    "L'article a bien été créé"
                );
                $this->redirect()->toRoute('manage');
            } else {
                $this->flashMessenger()->addErrorMessage(
                    "Erreur : Le formulaire n'est pas valide"
                );
            }
        }

        $this->layout('layout/front');
        return new ViewModel(array(
            'form' => $form,
            'autocompleteTagSource' => $autocompleteTagSource,
        ));
    }

    public function editAction()
    {
        $entityManager = $this->getEntityManager();

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('manage');
        }

        /** @var Article $article */
        $article = $entityManager->getRepository('Blog\Entity\Article')->find($id);
        if (!$article) {
            return $this->redirect()->toRoute('manage');
        }

        $form = new ArticleForm();
        $form->setInputFilter(new ArticleFilter());
        $form->setHydrator(new DoctrineHydrator($entityManager));

        $form->bind($article);

        $tags = $entityManager->getRepository('Blog\Entity\Tag')->findAll();
        $allTags = array();
        /** @var Tag $tag */
        foreach ($tags as $tag) {
            array_push($allTags, $tag->getTitle());
        }
        $autocompleteTagSource = json_encode($allTags);

        $form->get('tagsString')->setValue($article->getTagsString());

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);

            if ($form->isValid()) {
                // Traite file si on en a un
                if ($data['photofile']['name'] != '') {
                    $extension = pathinfo($data['photofile']['name'], PATHINFO_EXTENSION);
                    $oldfilename = Article::PHOTO_FOLDER . 'newphoto.' . $extension;
                    $newfilename = Article::PHOTO_FOLDER . $article->getId() . '.' . $extension;

                    rename($oldfilename, $newfilename);
                    create_square_image($newfilename, Article::PHOTO_FOLDER . 'thumbnail' .$article->getId() . '.' . $extension, 50);

                    $article->setPhoto($article->getId() . '.' . $extension);
                    $article->setThumbnail('thumbnail' . $article->getId() . '.' . $extension);
                }
                // Traite les Tags
                if ($data['tagsString'] != '') {
                    $tags = explode(' ', $data['tagsString']);
                    $tagsObjects = new ArrayCollection();
                    foreach ($tags as $tag) {
                        /** @var Tag $existingTag */
                        $existingTag = $entityManager->getRepository('BLog\Entity\Tag')
                            ->findOneBy(array('title' => $tag));
                        if (!is_null($existingTag)) {
                            $tagsObjects->add($existingTag);
                        } else {
                            $tagObject = new Tag();
                            $tagObject->setTitle($tag);
                            $tagsObjects->add($tagObject);
                        }
                    }
                    $article->setTags($tagsObjects);
                }
                $article->setWriter($this->identity());
                $entityManager->flush();

                $this->flashMessenger()->addSuccessMessage(
                    "L'article a bien été édité"
                );
                $this->redirect()->toRoute('manage');
            } else {
                var_dump($form->getMessages());
                $this->flashMessenger()->addErrorMessage(
                    "Erreur : Le formulaire n'est pas valide"
                );
            }
        }

        $this->layout('layout/front');
        return new ViewModel(array(
            'form' => $form,
            'autocompleteTagSource' => $autocompleteTagSource,
        ));
    }
}