<?php

namespace Admin\Service\Parser;

/**
 *
 */
interface ParseInterface
{

    /**
     * Функція для парсингу даних з сайту
     */
    public function parse();


    /**
     * Функція для імпорту даних з MongoDB в MySQL
     * @return mixed
     */
    public function import();
}