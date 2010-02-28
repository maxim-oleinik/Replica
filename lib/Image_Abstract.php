<?php

/**
 * Base image class
 *
 * @package    Replica
 * @subpackage Image
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
abstract class Replica_Image_Abstract
{
    const TYPE_PNG  = 'image/png';
    const TYPE_GIF  = 'image/gif';
    const TYPE_JPEG = 'image/jpeg';


    /**
     * Image width
     */
    private $_width;

    /**
     * Image height
     */
    private $_height;

    /**
     * Output image mime type
     */
    private $_type = self::TYPE_PNG;

    /**
     * Adapter resource
     */
    private $_resource;


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
     * Overlay image
     *
     * @param  int $x            - X-position
     * @param  int $y            - Y-position
     * @param  string $imagePath - Path to second image
     * @return $this
     */
    abstract public function overlay($x, $y, $imagePath);


    /**
     * Save file
     *
     * @param  string $fullName
     * @param  string $mimeType
     * @return void
     */
    abstract public function saveAs($fullName, $mimeType = null);


    /**
     * Reset adapter resource
     *
     * @return void
     */
    abstract protected function _doReset();


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
     * @param  string $mimeType
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
     * Get adapter resource
     *
     * @return resource
     */
    public function getResource()
    {
        $this->exceptionIfNotInitialized();
        return $this->_resource;
    }


    /**
     * If image is loaded
     *
     * @return bool
     */
    public function isInitialized()
    {
        return null !== $this->_resource;
    }


    /**
     * Initialize image
     *
     * @param  resource $resource - Adapter resource
     * @param  int      $$width
     * @param  int      $height
     * @return void
     */
    protected function _initialize($resource, $width, $height)
    {
        $this->_resource = $resource;

        $this->_width  = (int) $width;
        $this->_height = (int) $height;
    }


    /**
     * Reset image
     */
    public function reset()
    {
        if ($this->isInitialized()) {
            $this->_doReset();
        }

        $this->_resource = null;
        $this->_width    = null;
        $this->_height   = null;
        $this->_type     = null;
    }


    /**
     * Throw exception if image is not loaded
     *
     * @throws Replica_Exception_ImageNotInitialized
     */
    public function exceptionIfNotInitialized($message = null)
    {
        if (!$this->isInitialized()) {
            throw new Replica_Exception_ImageNotInitialized($message);
        }
    }

}
