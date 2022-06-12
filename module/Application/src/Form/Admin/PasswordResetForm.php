<?php

namespace Application\Form\Admin;

use Laminas\Form\Form;
use Laminas\Validator\Hostname;

class PasswordResetForm extends Form
{
    public function __construct()
    {
        parent::__construct('password-reset-form');

        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        $this->add([
            'type'  => 'email',
            'name' => 'email',
            'options' => [
                'label' => 'Your E-mail',
            ],
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
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Reset Password',
                'id' => 'submit',
            ],
        ]);
    }

    private function addInputFilter()
    {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name'     => 'email',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck'    => false,
                    ],
                ],
            ],
        ]);
    }
}