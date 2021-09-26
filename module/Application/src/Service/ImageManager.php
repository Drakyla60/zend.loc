<?php

namespace Application\Service;

class ImageManager
{
    /**
     * @var string
     */
    private string $saveToDir = './data/upload';

    /**
     * @return string
     */
    public function getSaveToDir(): string
    {
        return $this->saveToDir;
    }

    /**
     * @throws \Exception
     */
    public function getSavedFiles(): array
    {
        if (!is_dir($this->saveToDir)) {
            if (!mkdir($this->saveToDir)) {
                throw new \Exception('Не вдалося створити каталог для завантаження:' . error_get_last());
            }
        }

        $files = [];
        $handle = opendir($this->saveToDir);

        while (false !== ($entry = readdir($handle))) {
            if ($entry == '.' || $entry == '..') continue;

            $files[] = $entry;
        }

        return $files;
    }

    /**
     * @param $fileName
     * @return string
     */
    public function getImagePathByName($fileName): string
    {
        $fileName = str_replace('/', '', $fileName);
        $fileName = str_replace('\\', '', $fileName);

        return $this->saveToDir  . '/' . $fileName;
    }

    /**
     * @param $filePath
     * @return false|string
     */
    public function getImageFileContent($filePath)
    {
        return file_get_contents($filePath);
    }

    /**
     * @param $filePath
     * @return array|false
     */
    public function getImageFileInfo($filePath)
    {
        if (!is_readable($filePath)) return false;

        $fileSize = filesize($filePath);

        $fileInfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($fileInfo, $filePath);

        if ($mimeType===false) $mimeType = 'application/octet-stream';

        return [
            'size' => $fileSize,
            'type' => $mimeType
        ];
    }

    /**
     * Змінюєю розмір сторін зберігаючи співвідношення сторін
     * @param $filePath
     * @param int $desiredWidth
     * @return false|string
     */
    public  function resizeImage($filePath, int $desiredWidth = 240)
    {
        list($originalWidth, $originalHeight) = getimagesize($filePath);

        $aspectRatio = $originalWidth/$originalHeight;
        $desiredHeight = $desiredWidth/$aspectRatio;

        $fileInfo = $this->getImageFileInfo($filePath);

        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        if (substr($fileInfo['type'], 0, 9) =='image/png')
            $originalImage = imagecreatefrompng($filePath);
        else
            $originalImage = imagecreatefromjpeg($filePath);
        imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0,
            $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

        $tmpFileName = tempnam("/tmp", "FOO");
        imagejpeg($resultingImage, $tmpFileName, 80);

        return $tmpFileName;
    }

}