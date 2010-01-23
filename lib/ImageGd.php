<?php

class Replica_ImageGd
{
    /**
     * GD resource
     */
    protected $_resource;

    /**
     * Image width
     */
    protected $_width;

    /**
     * Image height
     */
    protected $_height;

    /**
     * Output image mime type
     */
    protected $_type;

    /**
     * Flag: image is loaded
     */
    protected $_isLoaded = false;


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
     * If image is loaded
     *
     * @return bool
     */
    public function isLoaded()
    {
        return $this->_isLoaded;
    }


    /**
     * Get image width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }


    /**
     * Get image height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }


    /**
     * Get image mime type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
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
        $this->_exceptionIfNotLoaded();

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
     * @param
     * @return $this
     */
    public function crop($x, $y, $width, $height)
    {
        $this->_exceptionIfNotLoaded();

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
     * Throw exception if image is not loaded
     *
     * @throws Replica_Exception
     */
    protected function _exceptionIfNotLoaded()
    {
        if (!$this->isLoaded()) {
            throw new Replica_Exception('Image NOT loaded');
        }
    }


    /**
     * Reset image
     */
    public function reset()
    {
        if ($this->isLoaded()) {
            imagedestroy($this->_resource);
            $this->_resource = null;
            $this->_width    = null;
            $this->_height   = null;
            $this->_type     = null;
            $this->_isLoaded = false;
        }
    }

}
