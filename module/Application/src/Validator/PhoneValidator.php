<?php

namespace Application\Validator;

use Laminas\Validator\AbstractValidator;

class PhoneValidator extends AbstractValidator
{
    const PHONE_FORMAT_LOCAL = 'local';
    const PHONE_FORMAT_INTL  = 'intl';

    protected $options = [
        'format' => self::PHONE_FORMAT_INTL
    ];


    const NOT_SCALAR  = 'notScalar';
    const INVALID_FORMAT_INTL  = 'invalidFormatIntl';
    const INVALID_FORMAT_LOCAL = 'invalidFormatLocal';

    protected array $messageTemplates = [
        self::NOT_SCALAR  => "Номер телефону повинен бути скалярним значеням",
        self::INVALID_FORMAT_INTL => "Номер телефону повинен бути в міжнародному форматі",
        self::INVALID_FORMAT_LOCAL => "Номер телефону повинен бути в локальному форматі",
    ];

    /**
     * @throws \Exception
     */
    public function __construct($options = null)
    {

        if(is_array($options)) {

            if(isset($options['format']))
                $this->setFormat($options['format']);
        }

        parent::__construct($options);
    }

    /**
     * @throws \Exception
     */
    public function setFormat($format)
    {
        if($format!=self::PHONE_FORMAT_LOCAL &&
            $format!=self::PHONE_FORMAT_INTL) {
            throw new \Exception('Invalid format argument passed.');
        }

        $this->options['format'] = $format;
    }

    public function isValid($value): bool
    {
        if(!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }

        $value = (string)$value;

        $format = $this->options['format'];

        if($format == self::PHONE_FORMAT_INTL) {
            $correctLength = 18;
            $pattern = '/^\+\d{1,2} \(\d{3}\) \d{3}-\d{4}$/'; // +00 (000) 000-0000
        } else { // self::PHONE_FORMAT_LOCAL
            $correctLength = 8;
            $pattern = '/^\d{3}-\d{4}$/';
        }

        $isValid = false;
        if(strlen($value)==$correctLength) {

            if(preg_match($pattern, $value))
                $isValid = true;
        }

        if(!$isValid) {
            if($format==self::PHONE_FORMAT_INTL)
                $this->error(self::INVALID_FORMAT_INTL);
            else
                $this->error(self::INVALID_FORMAT_LOCAL);
        }

        return $isValid;
    }
}