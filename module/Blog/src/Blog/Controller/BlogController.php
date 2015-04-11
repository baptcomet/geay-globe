<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Blog\Entity\Repository\ArticleRepository;
use Blog\Entity\Repository\TagRepository;
use Blog\Form\ContactForm;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part;

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

        $form = new ContactForm();

        $user = $this->identity();
        if ($user) {
            $form->get('firstname')->setValue($user->getFirstName());
            $form->get('lastname')->setValue($user->getLastName());
            $form->get('email')->setValue($user->getEmail());
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            //recuperation des valeurs postées et des filtres
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $formData = $form->getData();

                $firstname = $formData['firstname'];
                $lastname = $formData['lastname'];
                $emailFrom = $formData['email'];
                $subject = $formData['subject'];
                $message = $formData['message'];

                //$emailTo = 'contact@geays-globe.fr';
                $emailTo = 'geays.globe@yahoo.com';

                $body = 'Ce message a été envoyé depuis Geay\'s Globe le ' . date('d/m/Y à H:i') . '.' . PHP_EOL;
                $body .= 'Informations du Contact :' . PHP_EOL;
                $body .= PHP_EOL;
                $body .= "\t" . 'Prénom : ' . $firstname . PHP_EOL;
                $body .= "\t" . 'Nom : ' . $lastname . PHP_EOL;
                $body .= "\t" . 'Email : ' . $emailFrom . PHP_EOL;
                $body .= PHP_EOL;
                $body .= '------------------------------------------------------------------------------------------------------';
                $body .= PHP_EOL;
                $body .= 'Objet : ' . $subject . PHP_EOL;
                $body .= PHP_EOL;
                $body .= 'Message :' . PHP_EOL;
                $body .= $message;


                $headers = "From: " . $emailFrom;

                $sentMail = mail($emailTo, $subject, $body, $headers);

                if ($sentMail) {
                    $this->flashMessenger()->addSuccessMessage('Merci pour votre message! ;)');
                    return $this->redirect()->toRoute('contact');
                } else {
                    $this->flashMessenger()->addErrorMessage('Oups, le mail n\'est pas parti...');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('Un problème est survenu : le formulaire n\'est pas valide.');
            }
        }

        return new ViewModel(array(
            'form' => $form,
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
 * TODO Boite à idées :
 *
 *  1. Manage : 1 premier bloc pour gérer les catégories etc
 *  2. Une table 1 line "basics" et 1 form pour changer l'image background/une couleur, et le titre/sous-titre du blog
 *  3. Un slug à chaque article pour l'URL
 *  4. Page contact
 *  5. Naviguer entre articles
 *  6. Suggestions d'articles dans une box "like this"
 */
