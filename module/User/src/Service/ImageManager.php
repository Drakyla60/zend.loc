<?php

namespace User\Service;


use claviska\SimpleImage;

/**
 * Сервіс для роботи з зображеннями
 */
class ImageManager
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function uploadUserImage($data)
    {
        $pathCatalog = $this->config['images']['userImagesCatalog'];
        //@TODO Треба ще зробити видалення старого зображення
        if (null != $data['avatar']) {
            $path = $data['avatar']['tmp_name'];
            $fileName =  time() .'_'. $data['avatar']['name'];
            $savePath = $pathCatalog . $fileName;

            if (move_uploaded_file($path, $savePath)) {
                $data['avatar'] = $fileName;
            }
        }
        $this->resizeUploadImage($data, 50);
        $this->resizeUploadImage($data, 150);

        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function uploadContactUsImage($data)
    {
        $pathCatalog = $this->config['images']['contactUsImagesCatalog'];

        if (null != $data['file']) {
            $path = $data['file']['tmp_name'];
            $fileName =  time() .'_'. $data['file']['name'];
            $savePath = $pathCatalog . $fileName;

            if (move_uploaded_file($path, $savePath)) {
                $data['file'] = $savePath;
            }

        }
        return $data;
    }

    public function resizeUploadImage($data, $width = 0, $height = 0) {
//        $pathCatalog = $this->config['images'];
        if ($width == 50) {
            $pathCatalog = $this->config['images']['userImagesCatalog50x50'];
        } else {
            $pathCatalog = $this->config['images']['userImagesCatalog150x150'];
        }

        $image = new SimpleImage();
        $image
            ->fromFile($this->config['images']['userImagesCatalog'] . $data['avatar'])        // load image.jpg
            ->autoOrient()                                        // adjust orientation based on exif data
            ->resize($width, $height)                             // resize to 320x200 pixels
//                    ->flip('x')                                 // flip horizontally
//                    ->colorize('DarkBlue')                      // tint dark blue
//                    ->border('black', 10)                       // add a 10 pixel black border
//                    ->overlay('watermark.png', 'bottom right')  // add a watermark image
            ->toFile($pathCatalog . $data['avatar'], 'image/jpeg')      // convert to PNG and save a copy to new-image.png
//                    ->toScreen()
        ;
        return $data;
    }
}