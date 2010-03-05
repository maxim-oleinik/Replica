<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * ImageGd: overlay test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_ImageGd_OvewrlayTest extends ReplicaTestCase
{
    private
        $image,
        $overlay;

    /**
     * SetUp
     */
    public function _setup()
    {
        $this->image   = new Replica_Image_Gd($this->getFileNameInput('png_120x90'));
        $this->overlay = new Replica_Image_Gd($this->getFileNameInput('png_transparent_60x60'));
    }


    /**
     * Overlay
     */
    public function testSimpleOverlay()
    {
        $this->image->overlay($left = 20, $top = 10, $this->overlay);

        $this->assertImage($this->image, 120, 90, 'image/png');
        $this->image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameExpected(__METHOD__), $path);
    }


    /**
     * Overlay with negative coordinates
     */
    public function testOverlayWithNegativeCoordinates()
    {
        $this->image->overlay($left = -20, $top = -10, $this->overlay);

        $this->assertImage($this->image, 120, 90, 'image/png');
        $this->image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameExpected(__METHOD__), $path);
    }


    /**
     * Out of range
     */
    public function testOutOfRange()
    {
        $this->image->overlay($this->image->getWidth()+1, $this->image->getHeight()+1, $this->overlay);

        $this->image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->image, $path);
    }


    /**
     * Exception if source image not found
     */
    public function testExceptionIfSourceImageNotFound()
    {
        $this->setExpectedException('Replica_Exception_ImageNotInitialized', 'Overlay image not initialized');
        $this->image->overlay(0, 0, new Replica_Image_Gd);
    }


    /**
     * Overlay not modified
     */
    public function testOverlayNotModified()
    {
        $this->overlay->saveAs($path = $this->getFileNameActual('overlay'));

        $this->image->overlay(10, 20, $this->overlay);
        $this->assertImageFile($this->overlay, $path);
    }


}
