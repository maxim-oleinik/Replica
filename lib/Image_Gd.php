<?php

/**
 * GD image
 *
 * @package    Replica
 * @subpackage Image
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_Image_Gd extends Replica_Image_Abstract
{
    /**
     * Load image from file
     *
     * @param  string $filePath
     * @return bool
     */
    public function loadFromFile($filePath)
    {
        $this->reset();

        $info = $this->_getFileInfo($filePath);
        if (!$info) {
            return false;
        }

        return $this->loadFromString(file_get_contents($filePath), $info['type']);
    }


    /**
     * Load image from string
     *
     * @param  string $filePath
     * @param  string $type     - mime type
     * @return bool
     */
    public function loadFromString($data, $type = 'image/png')
    {
        $this->reset();

        $errLevel = error_reporting(0);
            $res = imagecreatefromstring($data);
        error_reporting($errLevel);

        if (!$res) {
            return false;
        }

        $this->_initialize($res, imagesx($res), imagesy($res));
        $this->setType($type);

        return true;
    }


    /**
     * Get file info
     *
     * @param  string $filePath
     * @return false|array
     */
    protected function _getFileInfo($filePath)
    {
        $result = false;
        $errorLevel = error_reporting(0);
            $arrProps = getimagesize($filePath);
            if ($arrProps) {
                $result = array(
                    'width'  => $arrProps[0],
                    'height' => $arrProps[1],
                    'type'   => $arrProps['mime']
                );
            }
        error_reporting($errorLevel);
        return $result;
    }


    /**
     * Resize image
     *
     * @param  int $width
     * @param  int $height
     * @return $this
     */
    public function resize($width, $height)
    {
        $res = $this->getResource();

        // Do not resize with same size
        if ($width == $this->getWidth() && $height == $this->getHeight()) {
            return $this;
        }

        $target = $this->_createTrueColor($width, $height);
        imagecopyresampled($target, $res, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        imagedestroy($res);

        $this->_initialize($target, $width, $height);
        return $this;
    }


    /**
     * Crop image
     *
     * @param  int $x      - src lef-top corner X
     * @param  int $y      - src lef-top corner Y
     * @param  int $width  - destination width
     * @param  int $height - destination height
     * @return $this
     */
    public function crop($x, $y, $width, $height)
    {
        $res = $this->getResource();

        $newWidth  = ($x + $width  > $this->getWidth())  ? $this->getWidth()  - $x : $width;
        $newHeight = ($x + $height > $this->getHeight()) ? $this->getHeight() - $y : $height;

        // Do not crop if same result
        if (!$x && !$y && $this->getWidth() == $newWidth && $this->getHeight() == $height) {
            return $this;
        }

        $target = $this->_createTrueColor($newWidth, $newHeight);
        imagecopy($target, $res, 0, 0, $x, $y, $newWidth, $newHeight);

        imagedestroy($res);
        $this->_initialize($target, $newWidth, $newHeight);

        return $this;
    }


    /**
     * Create GD image with white background
     *
     * @param int $width
     * @param int $height
     * @return GD resource
     */
    protected function _createTrueColor($width, $height)
    {
        $result = imagecreatetruecolor($width, $height);
        $background  = imagecolorallocate($result, 255, 255, 255);
        imagefill($result, 0, 0, $background);
        return $result;
    }


    /**
     * Save file
     *
     * @param  string $fullName
     * @param  string $mimeType
     * @return void
     */
    public function saveAs($fullName, $mimeType = null)
    {
        $res = $this->getResource();

        if ($mimeType) {
            $this->setType($mimeType);
        }

        switch ($this->getType()) {
            case self::TYPE_PNG:
                imagepng($res, $fullName, 9);
                break;

            case self::TYPE_GIF:
                imagegif($res, $fullName);
                break;

            case self::TYPE_JPEG:
                imagejpeg($res, $fullName);
                break;

            default:
                throw new Replica_Exception(__METHOD__.": Unknown image type `{$this->getType()}`");
        }
    }


    /**
     * Reset image
     */
    public function _doReset()
    {
        imagedestroy($this->getResource());
    }

}
