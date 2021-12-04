<?php

namespace User\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\InArray;
use Laminas\Validator\StringLength;
use User\Entity\PostCategory;

class PostCategoryForm extends Form
{
    public function __construct()
    {
        parent::__construct('post-category-form');
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();

    }

    protected function addElements()
    {
        $this->add([
            'type'  => Text::class,
            'name' => 'post-category-name',
            'attributes' => [
                'id'    => 'title',
                'class' =>'form-control',
                'placeholder'=>'Введіть заголовок Поста',
            ],
            'options' => [
                'label' => 'Введіть заголовок Поста',
            ],
        ]);

        $this->add([
            'type'  => Textarea::class,
            'name' => 'post-category-description',
            'attributes' => [
                'id'    => 'description',
                'class' =>'form-control',
                'placeholder'=>'Введіть опис Категорії',
            ],
            'options' => [
                'label' => 'Введіть опис Категорії',
            ],
        ]);

        // Добавляем поле "status"
        $this->add([
            'type'  => Select::class,
            'name' => 'post-category-status',
            'attributes' => [
                'id' => 'status',
                'class'=>'form-control',
            ],
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    PostCategory::CATEGORY_PUBLISHED => 'Опубліковано',
                    PostCategory::CATEGORY_DRAFT => 'Чорнивик',
                ]
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
            'name'     => 'post-category-name',
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
                        'max' => 255
                    ],
                ],
            ],
        ]);


        $inputFilter->add([
            'name'     => 'post-category-description',
            'required' => false,
            'filters'  => [
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 2048
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'post-category-status',
            'required' => true,
            'validators' => [
                [
                    'name' => InArray::class,
                    'options'=> [
                        'haystack' => [
                            PostCategory::CATEGORY_PUBLISHED,
                            PostCategory::CATEGORY_DRAFT
                        ],
                    ]
                ],
            ],
        ]);

    }
}