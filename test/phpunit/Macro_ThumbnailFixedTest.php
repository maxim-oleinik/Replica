<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * Macro: thumbnail fixed test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_Macro_ThumbnailFixedTest extends ReplicaTestCase
{
    /**
     * Get paramentes
     */
    public function testGetParameters()
    {
        $macro = new Replica_Macro_ThumbnailFixed($width = 10, $height = 15, $cropWidth = 'right', $cropHeight = 'bottom');
        $expected = array(
            'width'      => $width,
            'height'     => $height,
            'cropWidth'  => $cropWidth,
            'cropHeight' => $cropHeight,
        );
        $this->assertEquals($expected, $macro->getParameters());
    }


    /**
     * Thumbnail
     */
    public function testThumbnail()
    {
        $plan = array(
            'FitWidthAndHeight' => array('png_120x90', 40, 30),
            'CropWidth'         => array('png_120x90', 30, 30),
            'CropHeight'        => array('png_90x120', 30, 30),
        );

        $image = new Replica_Image_Gd;
        foreach ($plan as $testName => $item) {
            list($file, $newWith, $newHeight) = $item;

            $image->loadFromFile($this->getFileNameInput($file));

            $macro = new Replica_Macro_ThumbnailFixed($newWith, $newHeight);
            $macro->run($image);

            $fixtureName = 'ThumbnailFixedTest_' . $testName;
            $image->saveAs($path = $this->getFileNameActual($fixtureName));

            $this->assertImage($image, $newWith, $newHeight, 'image/png', $testName);
            $this->assertImageFile($this->getFileNameExpected($fixtureName), $path, $testName);
        }
    }


    /**
     * Crop width
     */
    public function testCropWidth()
    {
        $image = new Replica_Image_Gd;
        foreach (array('left', 'center', 'right') as $pos) {

            $image->loadFromFile($this->getFileNameInput('png_120x90'));

            $macro = new Replica_Macro_ThumbnailFixed($newWith = 30, $newHeight = 30, $pos);
            $macro->run($image);

            $this->assertImage($image, $newWith, $newHeight, 'image/png', $pos);
            // Save and compare with template
            $fixtureName = 'ThumbnailFixedTest_CropWidth_' . $pos;
            $image->saveAs($path = $this->getFileNameActual($fixtureName));
            $this->assertImageFile($this->getFileNameExpected($fixtureName), $path, $pos);
        }
    }


    /**
     * Crop height
     */
    public function testCropHeight()
    {
        $image = new Replica_Image_Gd;
        foreach (array('top', 'center', 'bottom') as $pos) {

            $image->loadFromFile($this->getFileNameInput('png_90x120'));

            $macro = new Replica_Macro_ThumbnailFixed($newWith = 30, $newHeight = 30, null, $pos);
            $macro->run($image);

            $this->assertImage($image, $newWith, $newHeight, 'image/png', $pos);
            // Save and compare with template
            $fixtureName = 'ThumbnailFixedTest_CropHeight_' . $pos;
            $image->saveAs($path = $this->getFileNameActual($fixtureName));
            $this->assertImageFile($this->getFileNameExpected($fixtureName), $path, $pos);
        }
    }


    /**
     * No changes if too small
     */
    public function testNoChangesIfTooSmall()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $macro = new Replica_Macro_ThumbnailFixed($newWith = 300, $newHeight = 300);
        $macro->run($image);

        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameInput('png_120x90'), $path);
    }

}
