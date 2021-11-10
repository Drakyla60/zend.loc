<?php

namespace Application\Form;

use Laminas\Form\Form;

class ChangeProfileSecurityForm extends Form
{
    public function __construct()
    {
        // Define form name
        parent::__construct('change-profile-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/profile/security');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        $this->add([
            'type'    => 'password',
            'name'    => 'old_password',
            'options' => [
                'label' => 'Старий пароль',
            ],
        ]);

        $this->add([
            'type'    => 'password',
            'name'    => 'new_password',
            'options' => [
                'label' => 'Новий пароль',
            ],
        ]);

        $this->add([
            'type'    => 'password',
            'name'    => 'confirm_new_password',
            'options' => [
                'label' => 'Підтвердіть новий пароль',
            ],
        ]);

        $this->add([
            'type'    => 'csrf',
            'name'    => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        $this->add([
            'type'       => 'submit',
            'name'       => 'submit',
            'attributes' => [
                'value' => 'Змінити пароль'
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        $inputFilter = $this->getInputFilter();
        // Add input for "old_password" field
        $inputFilter->add([
            'name'       => 'old_password',
            'required'   => true,
            'filters'    => [],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min'  => 6,
                        'max'  => 64
                    ],
                ],
            ],
        ]);


        // Add input for "new_password" field
        $inputFilter->add([
            'name'       => 'new_password',
            'required'   => true,
            'filters'    => [],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min'  => 6,
                        'max'  => 64
                    ],
                ],
            ],
        ]);

        // Add input for "confirm_new_password" field
        $inputFilter->add([
            'name'       => 'confirm_new_password',
            'required'   => true,
            'filters'    => [],
            'validators' => [
                [
                    'name'    => 'Identical',
                    'options' => [
                        'token' => 'new_password',
                    ],
                ],
            ],
        ]);
    }
}