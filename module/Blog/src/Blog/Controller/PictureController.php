<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Blog\Entity\Picture;
use Blog\Form\PictureForm;
use Zend\Filter\File\RenameUpload;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

class PictureController extends AbstractActionController
{
    public function imagesAction()
    {
        $id = $this->params()->fromRoute()['id'];
        $name = $this->params()->fromRoute()['name'];

        $imagePath = Article::BASE_UPLOAD_PATH . $id . '/' . Picture::FOLDER . '/' . $name;
        if (is_file($imagePath)) {
            $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
            if (in_array(strtolower($imageType), Picture::getStaticAuthorisedExtensionList())) {
                $imageData = file_get_contents($imagePath);
                /** @var \Zend\Http\Response $response */
                $response = $this->getResponse();
                $response->getHeaders()
                    ->addHeaderLine('Content-Type', 'image/'.$imageType)
                    ->addHeaderLine('Content-Length', mb_strlen($imageData));
                $response->setContent($imageData);
                return $response;
            }
        }
        die;
    }

    public function editAction()
    {
        if (is_null($this->identity())) {
            $this->redirect()->toRoute('blog');
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

        $savedFilename = $picture->getFilename();

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
                // Si on upload une nouvelle photo
                if ($picture->getFilename()) {
                    // TODO : resize image (http://php.net/manual/fr/function.imagecopyresized.php)
                    /** Déplacement de la photo */
                    $directory = Article::BASE_UPLOAD_PATH . $picture->getArticle()->getId() . '/' . Picture::FOLDER . '/';
                    // Créé le répertoire s'il n'existe pas
                    if (!is_dir($directory)) {
                        mkdir($directory, 0775, true);
                    }

                    $renameUpload = new RenameUpload(
                        array(
                            'target' => $directory . $picture->getFilename(),
                            'randomize' => true,
                        )
                    );
                    $newPath = $renameUpload->filter($picture->getTempFilename());
                    $newPathExplode = explode(DIRECTORY_SEPARATOR, $newPath);
                    $picture->setFilename($newPathExplode[sizeof($newPathExplode) - 1]);

                    $thumbnail = $directory . 'thumbnail_' . $picture->getFilename();
                    create_square_image($newPath, $thumbnail, 50);

                    // Suppression du fichier écrasé
                    $oldFile = $directory . $savedFilename;
                    unlink($oldFile);
                } else {
                    // Sinon on doit juste ne pas perdre le filename de la photo existante
                    $picture->setFilename($savedFilename);
                }

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
