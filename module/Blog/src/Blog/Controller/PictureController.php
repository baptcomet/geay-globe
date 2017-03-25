<?php

namespace Blog\Controller;

use Blog\Entity\Picture;
use Blog\Form\PictureForm;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

class PictureController extends AbstractActionController
{
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

        /** @var Picture $picture */
        $picture = $entityManager->getRepository('Blog\Entity\Picture')->find($id);
        if (!$picture) {
            return $this->redirect()->toRoute('manage');
        }

        // Création du formulaire
        $form = new PictureForm($entityManager);
        $form->bind($picture);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('La photo a bien été modifiée');
                return $this->redirect()
                    ->toRoute('article', array('action' => 'edit-pictures', 'id' => $picture->getArticle()->getId()));
            } else {
                $this->flashMessenger()->addSuccessMessage('Une erreur est survenue');
            }
        }

        $this->layout('layout/front');
        return new ViewModel(array(
            'form' => $form,
            'picture' => $picture,
        ));
    }
}
