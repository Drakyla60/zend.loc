<?php

namespace Application\Form;

use Laminas\Filter\File\RenameUpload;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Tel;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\FileInput;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\File\ImageSize;
use Laminas\Validator\File\IsImage;
use Laminas\Validator\File\MimeType;
use Laminas\Validator\File\UploadFile;
use Laminas\Validator\Hostname;
use Laminas\Validator\StringLength;

class ContactForm extends Form
{
    public function __construct()
    {
        parent::__construct('contact-form');

        $this->setAttribute('method', 'post');
//        $this->setAttribute('action', '/contact-us');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->addElements();

        $this->addInputFilter();
    }


    private function addElements(): void
    {

        $this->add([
            'type'  => Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        $this->add([
            'type'       => Email::class,
            'name'       => 'email',
            'attributes' => [
                'class'       => 'form-control',
                'placeholder' => 'name@example.com',
            ],
            'options'    => [
                'label' => 'Ваш Email ',
            ],
        ]);

        $this->add([
            'type'       => Text::class,
            'name'       => 'subject',
            'attributes' => [
                'class'       => 'form-control',
                'size'        => '30',
                'minlength'   => '6',
                'placeholder' => 'Type subject here'
            ],
            'options'    => [
                'label' => 'Введіть тему повідомлення',
            ],
        ]);

        $this->add([
            'type'       => Textarea::class,
            'name'       => 'body',
            'attributes' => [
                'class'       => 'form-control',
                'rows'        => '3',
                'placeholder' => 'Type message text here !'
            ],
            'options'    => [
                'label' => 'Введіть текст повідомлення',
            ],
        ]);

        $this->add([
            'type'       => Tel::class,
            'name'       => 'phone',
            'attributes' => [
                'class'       => 'form-control',
                'placeholder' => '+00 (000) 000-0000',
            ],
            'options'    => [
                'label' => 'Ваш номер телефону',
            ],
        ]);

        $this->add([
            'type'       => File::class,
            'name'       => 'file',
            'attributes' => [
                'data-title' => 'Сюди можна перенести файл з комп\'ютера',
                'class'      => 'form-control-file text-secondary font-weight-bold',
                'onchange'   => 'readUrl(this)'
            ],
            'options'    => [
                'label' => 'Виберіть файл',
            ],
        ]);

        $this->add([
            'type'       => Button::class,
            'name'       => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'class' => 'btn btn-success'
            ],
            'options'    => [
                'label' => 'Відправити',
            ],
        ]);
    }

    private function addInputFilter(): void
    {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name'       => 'email',
            'required'   => true,
            'filters'    => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name'    => EmailAddress::class,
                    'options' => [
                        'allow'      => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name'       => 'phone',
            'required'   => false,
        ]);


        $inputFilter->add([
            'name'       => 'subject',
            'required'   => true,
            'filters'    => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'       => 'body',
            'required'   => true,
            'filters'    => [
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 4096
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'type'       => FileInput::class,
            'name'       => 'file',
            'required'   => false,
            'validators' => [
                [
                    'name'    => UploadFile::class
                ],
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
//            'filters'    => [
//                [
//                    'name'    => RenameUpload::class,
//                    'options' => [
//                        'target'            => './data/contact-us',
//                        'useUploadName'     => true,
//                        'useUploadExtension'=> true,
//                        'overwrite'         => true,
//                        'randomize'         => true
//                    ]
//                ]
//            ],

        ]);
    }

}