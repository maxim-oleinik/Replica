<?php

class Replica_Image_Gd extends Replica_Image_Abstract
{
    /**
     * GD resource
     */
    protected $_resource;


    /**
     * Get image as GD resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->_resource;
    }


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

        $this->_resource = $res;

        $this->_type   = $type;
        $this->_width  = imagesx($res);
        $this->_height = imagesy($res);

        $this->_isLoaded = true;
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
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function resize($width, $height)
    {
        $this->exceptionIfNotLoaded();

        // Do not resize with same size
        if ($width == $this->_width && $height == $this->_height) {
            return;
        }

        $target = $this->_createTrueColor($width, $height);
        imagecopyresampled($target, $this->_resource, 0, 0, 0, 0, $width, $height, $this->_width, $this->_height);
        imagedestroy($this->_resource);
        $this->_resource = $target;

        $this->_width  = $width;
        $this->_height = $height;

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
        $this->exceptionIfNotLoaded();

        $this->_width   = ($x + $width  > $this->_width)  ? $this->_width  - $x : $width;
        $this->_height  = ($x + $height > $this->_height) ? $this->_height - $y : $height;

        $target = $this->_createTrueColor($this->_width, $this->_height);
        imagecopy($target, $this->_resource, 0, 0, $x, $y, $this->_width, $this->_height);

        imagedestroy($this->_resource);
        $this->_resource = $target;


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
        $this->exceptionIfNotLoaded();

        if ($mimeType) {
            $this->setType($mimeType);
        }

        switch ($this->_type) {
            case self::TYPE_PNG:
                imagepng($this->_resource, $fullName, 9);
                break;

            case self::TYPE_GIF:
                imagegif($this->_resource, $fullName);
                break;

            case self::TYPE_JPEG:
                imagejpeg($this->_resource, $fullName);
                break;

            default:
                throw new Replica_Exception(__METHOD__.": Unknown image type `{$this->_type}`");
        }
    }


    /**
     * Reset image
     */
    public function _doReset()
    {
        imagedestroy($this->_resource);
        $this->_resource = null;
    }

}
