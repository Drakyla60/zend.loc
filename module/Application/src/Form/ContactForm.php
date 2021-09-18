<?php
namespace Application\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\Validator\Hostname;

class ContactForm extends Form
{


    public function __construct()
    {
        parent::__construct();

        $this->addElements();

        $this->addInputFilter();
    }


    private function addInputFilter(): void
    {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name'     => 'email',
            'required' => true,
            'filters'  => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'subject',
            'required' => true,
            'filters'  => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'body',
            'required' => true,
            'filters'  => [
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 4096
                    ],
                ],
            ],
        ]);
    }

    private function addElements(): void
    {
        $this->add([
            'type' => Email::class,
            'name' => 'email',
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'subject',
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'body',
        ]);

//        $this->add([
//            'type' => Submit::class,
//            'name' => 'send',
//        ]);
    }
}