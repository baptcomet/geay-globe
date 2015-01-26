<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Blog\Form\ArticleForm;
use Blog\Form\Filter\ArticleFilter;
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

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $entityManager->persist($article);
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

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);

            if ($form->isValid()) {
                $extension = pathinfo($data['photofile']['name'], PATHINFO_EXTENSION);
                $oldfilename = Article::PHOTO_FOLDER . 'newphoto.' . $extension;
                $newfilename = Article::PHOTO_FOLDER . $article->getId() . '.' . $extension;

                rename($oldfilename, $newfilename);
                create_square_image($newfilename, Article::PHOTO_FOLDER . 'thumbnail' .$article->getId() . '.' . $extension, 50);

                $article->setPhoto($article->getId() . '.' . $extension);
                $article->setThumbnail('thumbnail' . $article->getId() . '.' . $extension);
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
        ));
    }
}