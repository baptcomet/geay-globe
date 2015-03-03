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
                $entityManager->persist($article);
                $entityManager->flush();

                // Traite file si on en a un TODO fix bug
                if ($data['photofile1']['name'] != '') {
                    $extension = pathinfo($data['photofile1']['name'], PATHINFO_EXTENSION);
                    $oldfilename = Article::PHOTO_FOLDER . 'newphoto1.' . $extension;
                    $newfilename = Article::PHOTO_FOLDER . $article->getId() . '_1.' . $extension;

                    rename($oldfilename, $newfilename);
                    create_square_image($newfilename, Article::PHOTO_FOLDER . 'thumbnail' .$article->getId() . '_1.' . $extension, 50);

                    $article->setPhoto1($article->getId() . '_1.' . $extension);
                    $article->setThumbnail1('thumbnail' . $article->getId() . '_1.' . $extension);
                }
                // Traite file si on en a un
                if ($data['photofile2']['name'] != '') {
                    $extension = pathinfo($data['photofile2']['name'], PATHINFO_EXTENSION);
                    $oldfilename = Article::PHOTO_FOLDER . 'newphoto2.' . $extension;
                    $newfilename = Article::PHOTO_FOLDER . $article->getId() . '_2.' . $extension;

                    rename($oldfilename, $newfilename);
                    create_square_image($newfilename, Article::PHOTO_FOLDER . 'thumbnail' .$article->getId() . '_2.' . $extension, 50);

                    $article->setPhoto2($article->getId() . '_2.' . $extension);
                    $article->setThumbnail2('thumbnail' . $article->getId() . '_2.' . $extension);
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
                if ($data['photofile1']['name'] != '') {
                    $extension = pathinfo($data['photofile1']['name'], PATHINFO_EXTENSION);
                    $oldfilename = Article::PHOTO_FOLDER . 'newphoto1.' . $extension;
                    $newfilename = Article::PHOTO_FOLDER . $article->getId() . '_1.' . $extension;

                    rename($oldfilename, $newfilename);
                    create_square_image($newfilename, Article::PHOTO_FOLDER . 'thumbnail' .$article->getId() . '_1.' . $extension, 50);

                    $article->setPhoto1($article->getId() . '_1.' . $extension);
                    $article->setThumbnail1('thumbnail' . $article->getId() . '_1.' . $extension);
                }
                // Traite file si on en a un
                if ($data['photofile2']['name'] != '') {
                    $extension = pathinfo($data['photofile2']['name'], PATHINFO_EXTENSION);
                    $oldfilename = Article::PHOTO_FOLDER . 'newphoto2.' . $extension;
                    $newfilename = Article::PHOTO_FOLDER . $article->getId() . '_2.' . $extension;

                    rename($oldfilename, $newfilename);
                    create_square_image($newfilename, Article::PHOTO_FOLDER . 'thumbnail' .$article->getId() . '_2.' . $extension, 50);

                    $article->setPhoto2($article->getId() . '_2.' . $extension);
                    $article->setThumbnail2('thumbnail' . $article->getId() . '_2.' . $extension);
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