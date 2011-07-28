<?php

/**
 * Proxies image loaded from file
 *
 * @package    Replica
 * @subpackage Cache
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_ImageProxy_FromFile extends Replica_ImageProxy_Abstract
{
    private $_file;


    /**
     * Construct
     *
     * @param  string $filePath - image file
     * @return void
     */
    public function __construct($filePath, $type = null, $quality = null)
    {
        $this->_file = (string) $filePath;
        if ($type) {
            $this->setMimeType($type);
        }
        if (null !== $quality) {
            $this->setQuality($quality);
        }
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
