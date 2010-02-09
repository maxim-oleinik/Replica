<?php
require_once dirname(__FILE__).'/../bootstrap.php';


class Replica_ImageGd_TransformTest extends ReplicaTestCase
{
    /**
     * Test: Resize image
     */
    public function testResize()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $image->resize(9, 9);
        $this->assertImage($image, 9, 9, 'image/png');
        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameExpected(__METHOD__), $path);

        // Equal size
        $image->loadFromFile($this->getFileNameInput('png_120x90'));
        $image->resize(120, 90);
        $image->saveAs($path = $this->getFileNameActual(__METHOD__).'_equals');
        $this->assertImageFile($this->getFileNameInput('png_120x90'), $path);
    }


    /**
     * Test: Resize empty image
     */
    public function testResizeEmptyImage()
    {
        $image = new Replica_Image_Gd;

        $this->setExpectedException('Replica_Exception');
        $image->resize(10, 10);
    }


    /**
     * Crop image
     */
    public function testCropImage()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $image->crop(0, 0, 10, 10);
        $this->assertImage($image, 10, 10, 'image/png');
        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameExpected(__METHOD__), $path);
    }


    /**
     * Crop image with coords shift
     */
    public function testCropWithShift()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $image->crop(100, 80, 200, 400);
        $this->assertImage($image, 20, 10, 'image/png');
        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameExpected(__METHOD__), $path);
    }

}
