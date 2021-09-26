<?php

namespace Application\Form;

use Laminas\Filter\File\RenameUpload;
use Laminas\Form\Form;
use Laminas\InputFilter\FileInput;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\File\ImageSize;
use Laminas\Validator\File\IsImage;
use Laminas\Validator\File\MimeType;
use Laminas\Validator\File\UploadFile;

class ImageForm extends Form
{
    public function __construct()
    {
        parent::__construct('image-form');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->addElement();

        $this->addInputFilter();
    }

    private function addElement()
    {
        $this->add([
            'type'       => 'file',
            'name'       => 'file',
            'attributes' => [
                'id' => 'file'
            ],
            'options'    => [
                'label' => 'Image file'
            ]
        ]);

        $this->add([
            'type'       => 'submit',
            'name'       => 'submit',
            'attributes' => [
                'value' => 'Upload',
                'id'    => 'submitButton',
                'class' => 'btn btn-primary'
            ]
        ]);
    }

    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $inputFilter->add([
            'type'       => FileInput::class,
            'name'       => 'file',
            'required'   => false,
            'validators' => [
//                [
//                    'name'    => UploadFile::class
//                ],
                [
                    'name'    => MimeType::class,
                    'options' => [
                        'mimeType'  => ['image/jpeg', 'image/png']
                    ]
                ],
                [
                    'name'    => IsImage::class
                ],
                [
                    'name'    => ImageSize::class,
                    'options' => [
                        'minWidth'  => 128,
                        'minHeight' => 128,
                        'maxWidth'  => 4096,
                        'maxHeight' => 4096
                    ]
                ],
            ],
            'filters'    => [
                [
                    'name'    => RenameUpload::class,
                    'options' => [
                        'target'            => './data/upload',
                        'useUploadName'     => true,
                        'useUploadExtension'=> true,
                        'overwrite'         => true,
                        'randomize'         => false
                    ]
                ]
            ],

        ]);
    }

}