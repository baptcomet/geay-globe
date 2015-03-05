<?php

namespace Blog\Controller;

use Blog\Entity\Article;
use Blog\Entity\Picture;

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
}
