<?php

namespace Application\Filter;

use Laminas\Filter\AbstractFilter;
use Laminas\Filter\Exception;

class PhoneFilter extends AbstractFilter
{

    const PHONE_FORMAT_LOCAL = 'local';
    const PHONE_FORMAT_INTL  = 'intl';

    protected $options = [
        'format' => self::PHONE_FORMAT_INTL
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
    }

    /**
     * @throws \Exception
     */
    public function setFormat($format)
    {
        if( $format!=self::PHONE_FORMAT_LOCAL &&
            $format!=self::PHONE_FORMAT_INTL ) {
            throw new \Exception('Invalid format argument passed.');
        }

        $this->options['format'] = $format;
    }

    public function getFormat()
    {
        return $this->options['format'];
    }


    public function filter($value)
    {
        if(!is_scalar($value)) {
            // Возвращаем нескалярное значение неотфильтрованным.
            return $value;
        }

        $value = (string)$value;

        if(strlen($value)==0) {
            // Возвращаем пустое значение неотфильтрованным.
            return $value;
        }

        // Сперва удаляем все нецифровые символы.
        $digits = preg_replace('#[^0-9]#', '', $value);

        $format = $this->getFormat();

        if($format == self::PHONE_FORMAT_INTL) {
            // Дополняем нулями, если число цифр некорректно.
            $digits = str_pad($digits, 11, "0", STR_PAD_LEFT);

            // Добавляем скобки, пробелы и тире.
            $phoneNumber = substr($digits, 0, 2) . ' (' .
                substr($digits, 2, 3) . ') ' .
                substr($digits, 5, 3) . '-' .
                substr($digits, 8, 2) . '-' .
                substr($digits, 10, 2);
        } else { // self::PHONE_FORMAT_LOCAL
            // Дополняем нулями, если число цифр некорректно
            $digits = str_pad($digits, 7, "0", STR_PAD_LEFT);

            // Добавляем тире.
            $phoneNumber = substr($digits, 0, 3) . '-'. substr($digits, 3, 4);
        }

        return $phoneNumber;
    }
}