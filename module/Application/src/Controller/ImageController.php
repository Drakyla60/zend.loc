<?php

namespace Application\Controller;

use Application\Form\ImageForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ImageController extends AbstractActionController
{
    private $imageManager;

    public function __construct($imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function indexAction()
    {
        $files = $this->imageManager->getSavedFiles();

        return new ViewModel([
            'files'=>$files
        ]);
    }

    public function uploadAction()
    {
        $form = new ImageForm('image-form');

        if($this->getRequest()->isPost()) {

            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);

            if($form->isValid()) {
                $data = $form->getData();

                $path = $data['file']['tmp_name'];
                $savePath = $this->imageManager->getSaveToDir(). '/' . $data['file']['name'];

                $result = move_uploaded_file($path, $savePath);
                //@TODO Треба зробити перевірку чи зберігся файл

                return $this->redirect()->toRoute('images');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function fileAction()
    {
        $fileName = $this->params()->fromQuery('name', '');
        $isThumbnail = (bool)$this->params()->fromQuery('thumbnail', false);
        $fileName = $this->imageManager->getImagePathByName($fileName);

        if($isThumbnail) {
            $fileName = $this->imageManager->resizeImage($fileName);
        }

        $fileInfo = $this->imageManager->getImageFileInfo($fileName);
        if (false === $fileInfo) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-type: " . $fileInfo['type']);
        $headers->addHeaderLine("Content-length: " . $fileInfo['size']);

        $fileContent = $this->imageManager->getImageFileContent($fileName);
        if(false !== $fileContent) {
            $response->setContent($fileContent);
        } else {
            $this->getResponse()->setStatusCode(500);
            return false;
        }

        if($isThumbnail) {
            unlink($fileName);
        }

        return $this->getResponse();
    }
}