<?php

namespace User\Service;

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

        if (null != $data['avatar']) {
            $path = $data['avatar']['tmp_name'];
            $fileName =  time() .'_'. $data['avatar']['name'];
            $savePath = $pathCatalog . $fileName;

            if (move_uploaded_file($path, $savePath)) {
                $data['avatar'] = $fileName;
            }
        }
        return $data;
    }

}