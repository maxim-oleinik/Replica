<?php


class Replica_ImageProxy_FromFile extends Replica_ImageProxy
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
     * @return Replica_ImageGD
     */
    protected function _loadImage(Replica_ImageAbstract $image)
    {
        $image->loadFromFile($this->_file);
    }

}
