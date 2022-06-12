<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\FileInput;
use Laminas\Validator\File\ImageSize;
use Laminas\Validator\File\IsImage;
use Laminas\Validator\File\MimeType;
use Laminas\Validator\Hostname;
use Application\Validator\Admin\UserExistsValidator;

class ChangeProfileSettingsForm extends Form
{

    private $entityManager;

    private $user;

    public function __construct($entityManager = null, $user = null)
    {
        parent::__construct('change-settings-form');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('action', '/profile/profile');
        $this->entityManager = $entityManager;
        $this->user = $user;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        $this->add([
            'type' => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'E-mail',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'full_name',
            'options' => [
                'label' => 'Логін',
            ],
        ]);

        $this->add([
            'type'       => 'file',
            'name'       => 'avatar',
            'attributes' => [
                'id' => 'file'
            ],
            'options'    => [
                'label' => 'Виберіть аватар'
            ]
        ]);

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
                [
                    'name' => UserExistsValidator::class,
                    'options' => [
                        'entityManager' => $this->entityManager,
                        'user' => $this->user
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'full_name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 512
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'type'       => FileInput::class,
            'name'       => 'avatar',
            'required'   => false,
            'validators' => [
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
        ]);


    }
}