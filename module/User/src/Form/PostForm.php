<?php

namespace User\Form;

use User\Entity\Post;
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

/**
 * Форма  для сбору даних про пост.
 */
class PostForm extends Form
{
    public function __construct()
    {
        parent::__construct('post-form');

        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * Этот метод добавляет элементы к форме (поля ввода и кнопку отправки формы).
     */
    protected function addElements()
    {

        // Добавляем поле "title"
        $this->add([
            'type'  => Text::class,
            'name' => 'title',
            'attributes' => [
                'id'    => 'title',
                'class' =>'form-control',
            ],
            'options' => [
                'label' => 'Enter post title here',
            ],
        ]);

        // Добавляем поле "content"
        $this->add([
            'type'  => Textarea::class,
            'name' => 'content',
            'attributes' => [
                'id' => 'content',
                'class'=>'form-control',
            ],
            'options' => [
                'label' => 'Type content here',
            ],
        ]);

        // Добавляем поле "tags"
        $this->add([
            'type'  => Text::class,
            'name' => 'tags',
            'attributes' => [
                'id' => 'tags',
                'class'=>'form-control',
            ],
            'options' => [
                'label' => 'comma, separated, list, of, tags',
            ],
        ]);

        // Добавляем поле "status"
        $this->add([
            'type'  => Select::class,
            'name' => 'status',
            'attributes' => [
                'id' => 'status',
                'class'=>'form-control',
            ],
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    Post::STATUS_PUBLISHED => 'Published',
                    Post::STATUS_DRAFT => 'Draft',
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
            'name'     => 'title',
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
                        'max' => 1024
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'content',
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

        $inputFilter->add([
            'name'     => 'tags',
            'required' => false,
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
                        'max' => 1024
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'status',
            'required' => true,
            'validators' => [
                [
                    'name' => InArray::class,
                    'options'=> [
                        'haystack' => [
                            Post::STATUS_PUBLISHED,
                            Post::STATUS_DRAFT
                        ],
                    ]
                ],
            ],
        ]);
    }
}