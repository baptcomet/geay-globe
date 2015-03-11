<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Blog\Entity\Picture;
use Blog\Entity\Tag;
use Blog\Form\ArticleForm;
use Blog\Form\Filter\ArticleFilter;
use Blog\Form\PictureForm;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Imagick;
use Zend\Filter\File\Rename;
use Zend\Filter\File\RenameUpload;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

class ArticleController extends AbstractActionController
{
    public function detailAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('home');
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
        if (is_null($this->identity())) {
            return $this->redirect()->toRoute('home');
        }

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
                $article->unpublish();
                $entityManager->persist($article);
                $entityManager->flush();

                $this->flashMessenger()->addSuccessMessage("L'article a bien été créé. Au tour des photos.");
                $this->redirect()->toRoute('article', array('action' => 'edit-pictures', 'id' => $article->getId()));
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
        if (is_null($this->identity())) {
            return $this->redirect()->toRoute('home');
        }

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
                $this->flashMessenger()->addErrorMessage(
                    "Erreur : Le formulaire n'est pas valide"
                );
            }
        }

        $this->layout('layout/front');
        return new ViewModel(array(
            'form' => $form,
            'article' => $article,
            'autocompleteTagSource' => $autocompleteTagSource,
        ));
    }

    public function editPicturesAction()
    {
        if (is_null($this->identity())) {
            return $this->redirect()->toRoute('home');
        }

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

        $picture = new Picture();
        // Création du formulaire
        $form = new PictureForm($entityManager);
        $form->bind($picture);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            /** @var \Zend\Stdlib\Parameters $data */
            $data = $request->getPost();
            $data->set('filename', $request->getFiles()->get('filename'));
            $form->setData($data);

            if ($form->isValid()) {
                /** Déplacement de la photo */
                $directory = Article::BASE_UPLOAD_PATH . $article->getId() . '/' . Picture::FOLDER . '/';
                // Créé le répertoire s'il n'existe pas
                if (!is_dir($directory)) {
                    mkdir($directory, 0775, true);
                }

                // Redimentionnement de l'image
                $image = new Imagick();
                $image->readImage($picture->getTempFilename());
                if ($image->getImageWidth() > 1120) {
                    $image->resizeImage(1120, 0, Imagick::FILTER_LANCZOS, 1);
                }
                $image->writeImage(realpath($directory) . DIRECTORY_SEPARATOR . 'img.tmp');
                $image->clear();

                $renamer = new Rename(
                    array(
                        'target' => $directory . $picture->getFilename(),
                        'randomize' => true,
                    )
                );

                $newPath = $renamer->filter($directory . 'img.tmp');
                $newPathExplode = explode(DIRECTORY_SEPARATOR, $newPath);
                $picture->setFilename($newPathExplode[sizeof($newPathExplode) - 1]);
                $picture->setArticle($article);

                // Suppression du fichier temporaire
                unlink($directory . 'img.tmp');

                $thumbnail = $directory . 'thumbnail_' . $picture->getFilename();
                create_square_image($newPath, $thumbnail, 50);

                $entityManager->persist($picture);
                $entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('La photo a bien été ajoutée à l\'article');
                return $this->redirect()->toRoute('article', array('action' => 'edit-pictures', 'id' => $id));
            } else {
                $this->flashMessenger()->addSuccessMessage('Une erreur est survenue');
            }
        }

        $this->layout('layout/front');
        return new ViewModel(array(
            'form' => $form,
            'article' => $article,
        ));
    }

    public function deletePictureAction()
    {
        if (is_null($this->identity())) {
            return $this->redirect()->toRoute('home');
        }

        $idArticle = (int)$this->params()->fromRoute('id', 0);
        $idPicture = (int)$this->params()->fromRoute('picture', 0);

        if (!$idPicture || !$idArticle) {
            return $this->redirect()->toRoute('manage');
        }

        $entityManager = $this->getEntityManager();
        /** @var Picture $picture */
        $picture = $entityManager->getRepository('Blog\Entity\Picture')->find($idPicture);
        if (is_null($picture)) {
            return $this->redirect()->toRoute('project');
        }

        // Suppression du fichier
        $file = Article::BASE_UPLOAD_PATH . $idArticle . '/' . Picture::FOLDER . '/' . $picture->getFilename();
        unlink($file);
        // Suppression de l'illustration en DB
        $entityManager->remove($picture);
        $entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('La photo a bien été retirée de l\'article');
        return $this->redirect()->toRoute('article', array('action' => 'edit-pictures', 'id' => $idArticle));
    }

    public function ajaxSwitchPublicationAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $data = $request->getPost();

        $entityManager = $this->getEntityManager();
        /** @var Article $article */
        $article = $entityManager->getRepository('Blog\Entity\Article')->find($data['id']);

        if (is_null($this->identity()) || !$article) {
            die;
        }

        // Switch l'état de publication de l'article
        $article->switchPublication();

        $entityManager->flush();

        return true;
    }
}
