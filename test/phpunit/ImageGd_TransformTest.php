<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * ImageGd: transform test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_ImageGd_TransformTest extends ReplicaTestCase
{
    /**
     * Resize image
     */
    public function testResize()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $image->resize(9, 9);
        $this->assertImage($image, 9, 9, 'image/png');
        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameExpected(__METHOD__), $path);
    }


    /**
     * Do not resize with equal size
     */
    public function testResizeWithEqualSize()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $res = $image->getResource();
        $image->resize(120, 90);
        $this->assertSame($res, $image->getResource());
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


    /**
     * Do not crop if same result
     */
    public function testDoNotCropIfSameResult()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $res = $image->getResource();
        $image->crop(0, 0, 120, 90);
        $this->assertSame($res, $image->getResource());
    }

}
