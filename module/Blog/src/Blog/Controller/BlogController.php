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

                $emailTo = 'contact@geays-globe.fr';

                $body = '<p>Ce message a été envoyé depuis <a href="http://geays-globe.fr" target="_blank">Geay\'s Globe</a> le ' . date('d/m/Y à H:i') . '.</p>' . PHP_EOL;
                $body .= '<h2 style="font-size:16px;border-bottom:1px solid #AAA;">Informations du Contact</h2>' . PHP_EOL;
                $body .= '<p>' . PHP_EOL;
                $body .= "\t" . '<b>Prénom :</b> ' . $firstname . '<br />' . PHP_EOL;
                $body .= "\t" . '<b>Nom :</b> ' . $lastname . '<br />' . PHP_EOL;
                $body .= "\t" . '<b>Email :</b> <a href="mailto:' . $emailFrom . '">' . $emailFrom . '</a>' . PHP_EOL;
                $body .= '</p>' . PHP_EOL;
                $body .= '<h2 style="font-size:16px;border-bottom:1px solid #AAA;">Message</h2>' . PHP_EOL;
                $body .= '<p><b>Objet :</b> ' . $subject . '</p>' . PHP_EOL;
                $body .= '<p>' . nl2br($message) . '</p>';

                $sentMail = $this->sendMail($emailTo, $body, $subject);

                if ($sentMail) {
                    $this->flashMessenger()->addSuccessMessage('Merci pour votre message! ;)');
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


    public function sendMail($to, $message, $title)
    {
        $html = '<html>' . PHP_EOL;
        $html .= "\t" . '<head>' . PHP_EOL;
        $html .= "\t" . '</head>' . PHP_EOL;
        $html .= "\t" . '<body>' . PHP_EOL;
        $html .= $message;
        $html .= "\t" . '</body>' . PHP_EOL;
        $html .= '</html>' . PHP_EOL;

        $bodyMessage = new \Zend\Mime\Message();
        $bodyPart = new Part($html);
        $bodyPart->type = 'text/html';
        $bodyPart->charset = 'utf-8';

        $bodyMessage->setParts(array($bodyPart));

        //$config = $this->getServiceLocator()->get('Configuration');

        $message = new Message();
        $message->addTo($to)
            ->addFrom('contact@geays-globe.fr')
            ->setSubject($title)
            ->setBody($bodyMessage);

        $mailSent = mail($to, $title, 'test message');

        return $mailSent;

//        $options = new SmtpOptions($config['mail']['transport']['options']);
//        $transport = new Smtp($options);
//        $transport->send($message);
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
