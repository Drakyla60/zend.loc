<?php

namespace Application\Form\Admin;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;

class PostTagForm extends Form
{
    public function __construct()
    {
        parent::__construct('post-tag-form');
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();

    }

    protected function addElements()
    {


        $this->add([
            'type'  => Text::class,
            'name' => 'post-tag-name',
            'attributes' => [
                'id'    => 'description',
                'class' =>'form-control',
                'placeholder'=>'Введіть Тег',
            ],
            'options' => [
                'label' => 'Введіть Тег',
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

        // Добавляем кнопку отправки формы
        $this->add([
            'type'  => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create',
                'class'=>'btn btn-primary',
                'id' => 'submitbutton',
            ],
        ]);
    }

    /**
     * Этот метод создает фильтр входных данных (используется для фильтрации/валидации).
     */
    private function addInputFilter()
    {

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name'     => 'post-tag-name',
            'required' => true,
            'filters'  => [
                [ 'name' => StringTrim::class ],
                [ 'name' => StripTags::class ],
                [ 'name' => StripNewlines::class ],
            ],
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 50
                    ],
                ],
            ],
        ]);

    }
}