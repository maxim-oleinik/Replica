<?php

abstract class Replica_Image_Abstract
{
    const TYPE_PNG  = 'image/png';
    const TYPE_GIF  = 'image/gif';
    const TYPE_JPEG = 'image/jpeg';


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
     * Set image mime type (to save image)
     *
     * @param  strnig $mimeType
     * @return void
     */
    public function setType($mimeType)
    {
        switch ($mimeType) {
            case self::TYPE_PNG:
            case self::TYPE_GIF:
            case self::TYPE_JPEG:
                $this->_type = $mimeType;
                break;

            default:
                throw new Replica_Exception(__METHOD__.": Unknown image type `{$mimeType}`");
        }
    }


    /**
     * Load image from file
     *
     * @param  string $filePath
     * @return bool
     */
    abstract public function loadFromFile($filePath);


    /**
     * Load image from string
     *
     * @param  string $filePath
     * @param  string $type     - mime type
     * @return bool
     */
    abstract public function loadFromString($data, $type = 'image/png');


    /**
     * Resize image
     *
     * @param  int $width
     * @param  int $height
     * @return $this
     */
    abstract public function resize($width, $height);


    /**
     * Crop image
     *
     * @param  int $x      - src lef-top corner X
     * @param  int $y      - src lef-top corner Y
     * @param  int $width  - destination width
     * @param  int $height - destination height
     * @return $this
     */
    abstract public function crop($x, $y, $width, $height);


    /**
     * Save file
     *
     * @param  string $fullName
     * @param  string $mimeType
     * @return void
     */
    abstract public function saveAs($fullName, $mimeType = null);


    /**
     * Reset image adpter implementation
     *
     * @return void
     */
    abstract protected function _doReset();


    /**
     * Reset image
     */
    public function reset()
    {
        if ($this->isLoaded()) {
            $this->_doReset();

            $this->_width    = null;
            $this->_height   = null;
            $this->_type     = null;
            $this->_isLoaded = false;
        }
    }


    /**
     * Throw exception if image is not loaded
     *
     * @throws Replica_Exception
     */
    public function exceptionIfNotLoaded()
    {
        if (!$this->isLoaded()) {
            throw new Replica_Exception('Image NOT loaded');
        }
    }

}
