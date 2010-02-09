<?php


class Replica_ImageProxy_FromFile extends Replica_ImageProxy_Abstract
{
    private $_file;


    /**
     * Construct
     *
     * @param  string $filePath - image file
     * @return void
     */
    public function __construct($filePath)
    {
        $this->_file = (string) $filePath;
    }


    /**
     * Get unique image ID
     *
     * @return string
     */
    public function getUid()
    {
        return $this->_file;
    }


    /**
     * Create image
     *
     * @return Replica_Image_Gd
     */
    protected function _loadImage(Replica_Image_Abstract $image)
    {
        $image->loadFromFile($this->_file);
    }

}
