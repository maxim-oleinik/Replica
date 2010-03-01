<?php

/**
 * Make thumbnail fits specified dimensions
 *
 * @package    Replica
 * @subpackage Macro
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_Macro_ThumbnailFit extends Replica_Macro_Abstract
{
    private $_maxWidth;
    private $_maxHeight;


    /**
     * Construct
     *
     * @param  int $maxWidth
     * @param  int $maxHeight
     * @return void
     */
    public function __construct($maxWidth, $maxHeight)
    {
        $this->_maxWidth  = $maxWidth;
        $this->_maxHeight = $maxHeight;
    }


    /**
     * Get macro parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return array(
            'maxWidth'  => $this->_maxWidth,
            'maxHeight' => $this->_maxHeight,
        );
    }


    /**
     * Run
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    protected function _doRun(Replica_Image_Abstract $image)
    {
        // Do not resize if origin is too small
        if ( $image->getWidth() < $this->_maxWidth && $image->getHeight() < $this->_maxHeight) {
            return;
        }

        // Scale
        $image->scale($this->_maxWidth, $this->_maxHeight);
    }

}
