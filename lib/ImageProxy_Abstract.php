<?php


abstract class Replica_ImageProxy_Abstract
{
    protected $_image;

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

        return $this->_image;
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
