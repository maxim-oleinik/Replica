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
     * Image quality
     */
    private $_quality;

    /**
     * Adapter resource
     */
    private $_resource;


    /**
     * Constructor
     *
     * @param  string $image - Path to image or binary data
     * @param  string $type  - Mime type if image loaded from string
     * @return void
     */
    public function __construct($image = null, $type = null)
    {
        if (null !== $image) {
            if (null !== $type) {
                $this->loadFromString($image, $type);
            } else {
                $this->loadFromFile($image);
            }
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
     * Overlay image
     *
     * @param  int $x  - left-top corner X-position, if negative right-bottom corner position
     * @param  int $y  - left-top corner Y-position, if negative right-bottom corner position
     * @param  Replica_Image_Abstract $image - Image to overlay
     * @return $this
     */
    abstract public function overlay($x, $y, Replica_Image_Abstract $image);


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
     * Get supported types
     *
     * @return array
     */
    static public function getMimeTypes()
    {
        return array(
            self::TYPE_PNG,
            self::TYPE_GIF,
            self::TYPE_JPEG,
        );
    }


    /**
     * Get image mime type
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->_type;
    }


    /**
     * Set image mime type (to save image)
     *
     * @param  string $mimeType
     * @return void
     */
    public function setMimeType($mimeType)
    {
        if (!in_array($mimeType, self::getMimeTypes())) {
            throw new Replica_Exception(__METHOD__.": Unknown image type `{$mimeType}`");
        }

        $this->_type = $mimeType;
    }


    /**
     * Set image quality
     *
     * @param  int  $perc - from 0 to 100
     * @return void
     */
    public function setQuality($perc)
    {
        $perc = (int) $perc;
        if ($perc < 0 || $perc > 100) {
            $perc = 100;
        }
        $this->_quality = $perc;
    }


    /**
     * Get image quality
     *
     * @return int
     */
    public function getQuality()
    {
        if (null === $this->_quality) {
            return $this->_getDefaultQuality();
        }

        return $this->_quality;
    }


    /**
     * Get default image quality
     *
     * PNG is 0
     * ALL is 100
     *
     * @return void
     */
    private function _getDefaultQuality()
    {
        switch ($this->_type) {
            case self::TYPE_PNG:
                return 0;

            default:
                return 100;
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
        return null !== $this->_resource && $this->_width > 0 && $this->_height > 0;
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
     * Scale image
     *
     * @param  int $width
     * @param  int $height
     * @return $this
     */
    public function scale($expectedWidth = null, $expectedHeight = null)
    {
        // Is initialized
        $this->getResource();

        if (!$expectedWidth && !$expectedHeight) {
            throw new InvalidArgumentException(get_class($this).'::scale: Expected width and/or height');
        }

        $thisWidth  = $this->getWidth();
        $thisHeight = $this->getHeight();

        // Scale
        $ratioSource = $thisWidth / $thisHeight;

        // Resize by width
        if (!$expectedHeight || ($expectedWidth && $ratioSource > $expectedWidth / $expectedHeight)) {
            $newWidth  = $expectedWidth;
            $newHeight = round($thisHeight * $newWidth / $thisWidth);

        // Resize by height
        } else {
            $newHeight = $expectedHeight;
            $newWidth  = round($thisWidth * $newHeight / $thisHeight);
        }

        $this->resize($newWidth, $newHeight);

        return $this;
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
        $this->_quality  = null;
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
