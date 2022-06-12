<?php

namespace Application\Form\Admin;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;

class CommentForm extends Form
{
    public function __construct()
    {
        parent::__construct('comment-form');
        
        $this->setAttribute('method', 'post');
        
        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements()
    {
        $this->add([
            'type'  => Text::class,
            'name'  => 'author',
            'attributes' => [
                'id'    => 'author',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Author',
            ],
        ]);

        $this->add([
            'type'  => Textarea::class,
            'name'  => 'comment',
            'attributes' => [
                'id'    => 'comment',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Comment',
            ],
        ]);

        $this->add([
            'type'  => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Save',
                'id' => 'submitbutton',
                'class'=>'btn btn-primary',
            ],
        ]);
    }

    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name'     => 'author',
            'required' => true,
            'filters'  => [
                ['name' => StringTrim::class],
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
            'name'     => 'comment',
            'required' => true,
            'filters'  => [
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
    }

}