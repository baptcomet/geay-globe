<?php

namespace Application\Controller;

use Blog\Entity\User;
use Blog\Form\AuthForm;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/login');

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = new AuthForm();

        /** @var Request $request */
        $request = $this->getRequest();
        //si connecté, redirection vers la liste des articles
        /** @var User $identity */
        $identity = $this->identity();

        if (isset($identity) && $identity->getId() > 0) {
            $this->redirect()->toRoute('home');
        }
        if ($request->isPost()) {
            //recuperation des valeurs postées et des filtres
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                /** @var AuthenticationService $authService */
                $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

                $adapter = $authService->getAdapter();
                $adapter->setIdentity($data['nickname']);
                $adapter->setCredential($data['password']);
                $authResult = $authService->authenticate();

                if ($authResult->isValid()) {
                    $objectManager->persist($this->identity());
                    $objectManager->flush();

                    return $this->redirect()->toRoute('home');
                } else { // Si erreur d'authentification
                    $this->flashMessenger()->addErrorMessage(
                        'La connexion a échoué. Le pseudo ou le mot de passe sont invalides'
                    );
                    return $this->redirect()->toRoute('login');
                }
            }
        }

        $view = new ViewModel();
        $view->setTemplate('application/index/index');

        return $view->setVariables(
            array(
                'form' => $form,
            )
        );
    }

    public function logoutAction()
    {
        $auth = new AuthenticationService();
        $auth->clearIdentity();
        return $this->redirect()->toRoute('home');
    }
}
