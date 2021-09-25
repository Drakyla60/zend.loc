<?php

namespace Application\Form;

use Application\Filter\PhoneFilter;
use Application\Validator\PhoneValidator;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Tel;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\FileInput;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Hostname;
use Laminas\Validator\StringLength;

class ContactForm extends Form
{
    public function __construct()
    {
        parent::__construct('contact-form');

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/contact-us');

        $this->addElements();

        $this->addInputFilter();
    }

    private function addInputFilter(): void
    {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => EmailAddress::class,
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'phone',
            'required' => false,
//            'filters' => [
//                [
//                    'name' => PhoneFilter::class,
//                    'options' => [
//                        'format' => PhoneFilter::PHONE_FORMAT_INTL
//                    ],
//                ],
//            ],
            'validators' => [
                [
                        'name' => PhoneValidator::class,
                        'options' => [
                            'format' => PhoneValidator::PHONE_FORMAT_INTL
                        ]
                ],
            ],
        ]);


        $inputFilter->add([
            'name' => 'subject',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'body',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 4096
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'type'     => FileInput::class,
            'name'     => 'file',
            'required' => false,
            'filters'  => [],
            'validators' => []
        ]);
    }

    private function addElements(): void
    {
        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'name@example.com',
            ],
            'options' => [
                'label' => 'Ваш Email ',
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'subject',
            'attributes' => [
                'class' => 'form-control',
                'size' => '30',
                'minlength' => '6',
                'placeholder' => 'Type subject here'
            ],
            'options' => [
                'label' => 'Введіть тему повідомлення',
            ],
        ]);

        $this->add([
            'type' => Textarea::class,
            'name' => 'body',
            'attributes' => [
                'class' => 'form-control',
                'rows' => '3',
                'placeholder' => 'Type message text here !'
            ],
            'options' => [
                'label' => 'Введіть текст повідомлення',
            ],
        ]);

        $this->add([
            'type' => Tel::class,
            'name' => 'phone',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => '+00 (000) 000-0000',
            ],
            'options' => [
                'label' => 'Ваш номер телефону',
            ],
        ]);

        $this->add([
            'type'  => File::class,
            'name' => 'file',
            'attributes' => [
//                'id' => 'input-file-now',
                'data-title' => 'Drag and drop a file',
                //@TODO  Проблема з відображенням при генеруванні класи кодуються і не відображається правильно шаблон
                'class' => 'form-control file text-danger font-weight-bold',
                'onchange' => 'readUrl(this)'
            ],
            'options' => [
                'label' => 'Upload file',

            ],
        ]);

        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-success'
            ],
            'options' => [
                'label' => 'Відправити',
            ],
        ]);

    }
}