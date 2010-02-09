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
     * @return Replica_ImageGD
     */
    abstract protected function _loadImage(Replica_ImageAbstract $image);


    /**
     * Get image
     *
     * @return Replica_ImageGD
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
     * @return Replica_ImageGd
     */
    protected function _createImage()
    {
        return new Replica_ImageGd;
    }

}
