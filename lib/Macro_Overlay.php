<?php

/**
 * Overlay macro
 *
 * @package    Replica
 * @subpackage Macro
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_Macro_Overlay extends Replica_Macro_Abstract
{
    private $_posX;
    private $_posY;
    private $_imagePath;


    /**
     * Construct
     *
     * @param  string $imagePath - Path to image
     * @param  int $x            - X-position
     * @param  int $y            - Y-position
     * @return void
     */
    public function __construct($x, $y, $imagePath)
    {
        $this->_posX = (int) $x;
        $this->_posY = (int) $y;
        $this->_imagePath = (string) $imagePath;
    }


    /**
     * Get macro parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return array(
            'posX'  => $this->_posX,
            'posY'  => $this->_posY,
            'image' => $this->_imagePath,
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
        $image->overlay($this->_posX, $this->_posY, $this->_imagePath);
    }

}
