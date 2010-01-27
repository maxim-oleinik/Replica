<?php


abstract class Replica_ImageProxy
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
    abstract protected function _createImage();


    /**
     * Get image
     *
     * @return Replica_ImageGD
     */
    public function getImage()
    {
        if (!$this->_image) {
            $this->_image = $this->_createImage();
        }

        return $this->_image;
    }

}
