<?php

/**
 * Base image proxy class
 *
 * @package    Replica
 * @subpackage Cache
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
abstract class Replica_ImageProxy_Abstract
{
    protected
        $_image,
        $_mimetype = Replica_Image_Abstract::TYPE_PNG,
        $_quality;


    /**
     * Get unique image ID
     *
     * @return int|string
     */
    abstract public function getUid();


    /**
     * Create image
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    abstract protected function _loadImage(Replica_Image_Abstract $image);


    /**
     * Set mime type
     *
     * @param  string $type
     * @return void
     */
    public function setMimeType($type)
    {
        $this->_mimetype = $type;
    }


    /**
     * Set mime type
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->_mimetype;
    }


    /**
     * Set quality
     *
     * @param  int $perc - 0-100
     * @return void
     */
    public function setQuality($perc)
    {
        $this->_quality = $perc;
    }


    /**
     * Get image
     *
     * @return Replica_Image_Abstract
     */
    public function getImage()
    {
        if (!$this->_image) {
            $this->_image = $this->_createImage();
            $this->_loadImage($this->_image);
        }

        $this->_syncProps($this->_image);
        return $this->_image;
    }


    /**
     * Synchronize image props with proxy
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    private function _syncProps(Replica_Image_Abstract $image)
    {
        $image->setMimeType($this->_mimetype);

        if (null !== $this->_quality) {
            $image->setQuality($this->_quality);
        }
    }


    /**
     * Create image instance
     *
     * @return Replica_Image_Gd
     */
    protected function _createImage()
    {
        return new Replica_Image_Gd;
    }

}
