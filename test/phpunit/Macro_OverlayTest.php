<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * Macro: overlay test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_Macro_OverlayTest extends ReplicaTestCase
{
    /**
     * Get paramentes
     */
    public function testGetParameters()
    {
        $path = $this->getFileNameInput('png_transparent_60x60');
        $macro = new Replica_Macro_Overlay($posX = 10, $posY = 15, $path);
        $expected = array(
            'posX'  => $posX,
            'posY'  => $posY,
            'image' => $path,
        );
        $this->assertEquals($expected, $macro->getParameters());
    }


    /**
     * Overlay
     */
    public function testOverlay()
    {
        $path = $this->getFileNameInput('png_transparent_60x60');
        $macro = new Replica_Macro_Overlay($posX = 10, $posY = 15, $path);

        $image = $this->getMock('Replica_Image_Gd', array('overlay'));
        $image->loadFromFile($this->getFileNameInput('png_120x90'));
        $image->expects($this->once())
              ->method('overlay')
              ->with($posX, $posY, $path);

        $macro->run($image);
    }

}
