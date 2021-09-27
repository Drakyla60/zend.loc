<?php

namespace Application\Form;

use Application\Validator\PhoneValidator;
use Exception;
use Laminas\Filter\StringToUpper;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\I18n\Filter\Alpha;
use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Between;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Hostname;
use Laminas\Validator\Identical;
use Laminas\Validator\InArray;
use Laminas\Validator\StringLength;

class RegistrationForm extends Form
{
    /**
     * @throws Exception
     */
    public function __construct($step)
    {
        if (!is_int($step) || $step < 1 || $step > 3) {
            throw new Exception('Step is Invalid');
        }

        parent::__construct('registration-form');

        $this->setAttribute('method', 'post');
//        $this->setAttribute('action', '/contact-us');

        $this->addElements($step);
        $this->addInputFilter($step);
    }

    private function addElements(int $step)
    {
        if ($step==1) {

            $this->add([
                'type'  => Email::class,
                'name' => 'email',
                'attributes' => [
                    'id' => 'email',
                    'class'       => 'form-control',
                    'placeholder' => 'name@example.com'
                ],
                'options' => [
                    'label' => 'Ваш E-mail',
                ],
            ]);


            $this->add([
                'type'  => Text::class,
                'name' => 'full_name',
                'attributes' => [
                    'id' => 'full_name',
                    'class'       => 'form-control',
                    'placeholder' => 'Petrenko Petro',
                ],
                'options' => [
                    'label' => 'Ваше повне імя',
                ],
            ]);

            $this->add([
                'type'  => Password::class,
                'name' => 'password',
                'attributes' => [
                    'id' => 'password',
                    'class'       => 'form-control',
                    'placeholder' => '********',
                ],
                'options' => [
                    'label' => 'Ваш пароль',
                ],
            ]);

            $this->add([
                'type'  => Password::class,
                'name' => 'confirm_password',
                'attributes' => [
                    'id' => 'confirm_password',
                    'class'       => 'form-control',
                    'placeholder' => '********',
                ],
                'options' => [
                    'label' => 'Підтвердіть пароль',
                ],
            ]);
        } else if ($step==2) {

            $this->add([
                'type'  => Text::class,
                'name' => 'phone',
                'attributes' => [
                    'id' => 'phone',
                     'class'       => 'form-control',
                    'placeholder' => '+00 (000) 000-0000',
                    'value' => '+00 (000) 000-0000',
                ],
                'options' => [
                    'label' => 'Ваш номер телефону',
                ],
            ]);

            $this->add([
                'type'  => Text::class,
                'name' => 'street_address',
                'attributes' => [
                    'id' => 'street_address',
                    'class'       => 'form-control',
                    'placeholder' => 'Ваша Вулиця',
                ],
                'options' => [
                    'label' => 'Ваша Вулиця',
                ],
            ]);

            $this->add([
                'type'  => Text::class,
                'name' => 'city',
                'attributes' => [
                    'id' => 'city',
                    'class'       => 'form-control',
                    'placeholder' => 'Ваше Місто',
                ],
                'options' => [
                    'label' => 'Ваше Місто',
                ],
            ]);

            $this->add([
                'type'  => Text::class,
                'name' => 'state',
                'attributes' => [
                    'id' => 'state',
                    'class'       => 'form-control',
                    'placeholder' => 'Ваша Область',
                ],
                'options' => [
                    'label' => 'Ваша область',
                ],
            ]);

            $this->add([
                'type'  => Text::class,
                'name' => 'post_code',
                'attributes' => [
                    'id' => 'post_code',
                    'class'       => 'form-control',
                    'placeholder' => 'Ваша поштова адреса 00000',
                ],
                'options' => [
                    'label' => 'Поштова адреса (код)',
                ],
            ]);

            $this->add([
                'type'  => Select::class,
                'name' => 'country',
                'attributes' => [
                    'id' => 'country',
                    'class'       => 'form-control',
                ],
                'options' => [
                    'label' => 'Країна',
                    'empty_option' => '-- Виберіть, будь-ласка --',
                    'value_options' => [
                        'UA' => 'Ukraine',
                        'US' => 'United States',
                        'CA' => 'Canada',
                        'BR' => 'Brazil',
                        'GB' => 'Great Britain',
                        'FR' => 'France',
                        'IT' => 'Italy',
                        'DE' => 'Germany',
                        'RU' => 'Russia',
                        'IN' => 'India',
                        'CN' => 'China',
                        'AU' => 'Australia',
                        'JP' => 'Japan'
                    ],
                ],
            ]);
        } else if ($step==3) {

            $this->add([
                'type'  => Select::class,
                'name' => 'billing_plan',
                'attributes' => [
                    'id' => 'billing_plan',
                    'class' => 'form-control',
                ],
                'options' => [
                    'label' => 'Тарифний план',
                    'empty_option' => '-- Виберіть, будь-ласка --',
                    'value_options' => [
                        'Free' => 'Free',
                        'Bronze' => 'Bronze',
                        'Silver' => 'Silver',
                        'Gold' => 'Gold',
                        'Platinum' => 'Platinum'
                    ],
                ],
            ]);

            $this->add([
                'type'  => Select::class,
                'name' => 'payment_method',
                'attributes' => [
                    'id' => 'payment_method',
                    'class' => 'form-control',
                ],
                'options' => [
                    'label' => 'Спосіб Оплати',
                    'empty_option' => '-- Виберіть, будь-ласка --',
                    'value_options' => [
                        'Visa' => 'Visa',
                        'MasterCard' => 'Master Card',
                        'PayPal' => 'PayPal'
                    ],
                ],
            ]);
        }

        $this->add([
            'type'  => Csrf::class,
            'name' => 'csrf',
            'attributes' => [],
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);


        $this->add([
            'type'  => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Наступний крок',
                'id' => 'submitButton',
                'class' => 'btn btn-success'
            ],
        ]);

    }

    private function addInputFilter(int $step)
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        if ($step==1) {

            $inputFilter->add([
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => EmailAddress::class,
                        'options' => [
                            'allow' => Hostname::ALLOW_DNS,
                            'useMxCheck'    => false,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name'     => 'full_name',
                'required' => true,
                'filters'  => [
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
                'name'     => 'password',
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name'     => 'confirm_password',
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name'    => Identical::class,
                        'options' => [
                            'token' => 'password',
                        ],
                    ],
                ],
            ]);

        } else if ($step==2) {

            $inputFilter->add([
                'name'     => 'phone',
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min' => 3,
                            'max' => 32
                        ],
                    ],
                    [
                        'name' => PhoneValidator::class,
                        'options' => [
                            'format' => PhoneValidator::PHONE_FORMAT_INTL
                        ]
                    ],
                ],
            ]);

            $inputFilter->add([
                'name'       => 'street_address',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => StringTrim::class
                    ],
                ],
                'validators' => [
                    [
                        'name'    =>StringLength::class,
                        'options' =>
                            [
                                'min' => 1,
                                'max' => 255
                            ]
                    ]
                ],
            ]);

            $inputFilter->add([
                'name'       => 'city',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => StringTrim::class
                    ],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' =>
                            [
                                'min' => 1,
                                'max' => 255
                            ]
                    ]
                ],
            ]);

            $inputFilter->add([
                'name'       => 'state',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => StringTrim::class
                    ],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' =>
                            [
                                'min' => 1,
                                'max' => 32
                            ]
                    ]
                ],
            ]);

            $inputFilter->add([
                'name'     => 'post_code',
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    ['name' => IsInt::class],
                    ['name'=> Between::class, 'options'=>['min'=>0, 'max'=>999999]]
                ],
            ]);

            $inputFilter->add([
                'name'     => 'country',
                'required' => false,
                'filters'  => [
                    ['name' => Alpha::class],
                    ['name' => StringTrim::class],
                    ['name' => StringToUpper::class],
                ],
                'validators' => [
                    ['name'=>StringLength::class, 'options'=>['min'=>2, 'max'=>2]]
                ],
            ]);

        } else if ($step==3) {

            $inputFilter->add([
                'name'     => 'billing_plan',
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name' => InArray::class,
                        'options' => [
                            'haystack'=>[
                                'Free',
                                'Bronze',
                                'Silver',
                                'Gold',
                                'Platinum'
                            ]
                        ]
                    ]
                ],
            ]);

            $inputFilter->add([
                'name'     => 'payment_method',
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name' => InArray::class,
                        'options' => [
                            'haystack'=>[
                                'PayPal',
                                'Visa',
                                'MasterCard',
                            ]
                        ]
                    ]
                ],
            ]);
        }
    }
}