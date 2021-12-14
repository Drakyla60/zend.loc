<?php

namespace Admin\Form;

use Laminas\InputFilter\FileInput;
use Laminas\Validator\File\ImageSize;
use Laminas\Validator\File\IsImage;
use Laminas\Validator\File\MimeType;
use Admin\Entity\Post;
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
    private $writeUser;
    private $categories;

    public function __construct($writeUser, $categories)
    {
        parent::__construct('post-form');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('method', 'post');

        $this->writeUser  = $writeUser;
        $this->categories = $categories;
        $this->addElements();
        $this->addInputFilter();

    }

    /**
     * Этот метод добавляет элементы к форме (поля ввода и кнопку отправки формы).
     */
    protected function addElements()
    {

        $this->add([
            'type'  => Select::class,
            'name' => 'author_id',
            'attributes' => [
                'id' => 'author_id',
                'class'=>'form-control',
            ],
            'options' => [
                'label' => 'Виберіть Автора',
                'value_options' => $this->writeUser
            ],
        ]);
        $this->add([
            'type'  => Select::class,
            'name' => 'category_id',
            'attributes' => [
                'id' => 'category_id',
                'class'=>'form-control',
            ],
            'options' => [
                'label' => 'Виберіть Категорію',
                'value_options' => $this->categories,
                'default' => 3
            ],
        ]);

        // Добавляем поле "title"
        $this->add([
            'type'  => Text::class,
            'name' => 'title',
            'attributes' => [
                'id'    => 'title',
                'class' =>'form-control',
                'placeholder'=>'Введіть заголовок Поста',
            ],
            'options' => [
                'label' => 'Введіть заголовок Поста',
            ],
        ]);

        // Добавляем поле "content"
        $this->add([
            'type'  => Textarea::class,
            'name' => 'content',
            'attributes' => [
                'id' => 'content',
                'class'=>'form-control',
                'placeholder'=>'Введіть контент Поста',
            ],
            'options' => [
                'label' => 'Введіть контент Поста',
            ],
        ]);

        $this->add([
            'type'  => Textarea::class,
            'name' => 'description',
            'attributes' => [
                'id'    => 'description',
                'class' =>'form-control',
                'placeholder'=>'Введіть опис Поста',
            ],
            'options' => [
                'label' => 'Введіть опис Поста',
            ],
        ]);

         //Добавляем поле "tags"
        $this->add([
            'type'  => Text::class,
            'name' => 'tags',
            'attributes' => [
                'id' => 'tags',
                'class'=>'form-control',
                'placeholder'=>'тег1, тег2,',
            ],
            'options' => [
                'label' => 'Напишіть, теги, через, ","',
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
                    Post::STATUS_PUBLISHED => 'Опубліковано',
                    Post::STATUS_DRAFT => 'Чорнивик',
                ]
            ],
        ]);

        $this->add([
            'type'       => 'file',
            'name'       => 'image',
            'attributes' => [
                'id' => 'file'
            ],
            'options'    => [
                'label' => 'Виберіть файл'
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
                        'max' => 16384
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'description',
            'required' => true,
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

        $inputFilter->add([
            'type'       => FileInput::class,
            'name'       => 'image',
            'required'   => false,
            'validators' => [
//                [
//                    'name'    => UploadFile::class
//                ],
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