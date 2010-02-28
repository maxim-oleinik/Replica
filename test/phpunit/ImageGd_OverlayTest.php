<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * ImageGd: overlay test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_ImageGd_OvewrlayTest extends ReplicaTestCase
{
    /**
     * Overlay
     */
    public function testSimpleOverlay()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $logo = $this->getFileNameInput('png_transparent_60x60');
        $image->overlay($left = 20, $top = 10, $logo);

        $this->assertImage($image, 120, 90, 'image/png');
        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameExpected(__METHOD__), $path);
    }


    /**
     * Overlay with negative coordinates
     */
    public function testOverlayWithNegativeCoordinates()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $logo = $this->getFileNameInput('png_transparent_60x60');
        $image->overlay($left = -20, $top = -10, $logo);

        $this->assertImage($image, 120, 90, 'image/png');
        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameExpected(__METHOD__), $path);
    }


    /**
     * Out of range
     */
    public function testOutOfRange()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($input = $this->getFileNameInput('png_120x90'));

        $logo = $this->getFileNameInput('png_transparent_60x60');
        $image->overlay($image->getWidth()+1, $image->getHeight()+1, $logo);

        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($input, $path);
    }


    /**
     * Exception if source image not found
     */
    public function testExceptionIfSourceImageNotFound()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($input = $this->getFileNameInput('png_120x90'));

        $logo = $this->getFileNameInput('unknown');
        $this->setExpectedException('Replica_Exception_ImageNotInitialized', 'Overlay image not initialized');
        $image->overlay(0, 0, $logo);
    }


    /**
     * Accept ImageGd object
     */
    public function testAcceptImageGbObject()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($input = $this->getFileNameInput('png_120x90'));

        $logo = new Replica_Image_Gd;
        $logo->loadFromFile($this->getFileNameInput('png_transparent_60x60'));

        $image->overlay(20, 10, $logo);
        $image->saveAs($path = $this->getFileNameActual($name = __CLASS__.'::testSimpleOverlay'));
        $this->assertImageFile($this->getFileNameExpected($name), $path);
    }
}
