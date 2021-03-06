<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * Macro: thumbnail fit test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_Macro_ThumbnailFitTest extends ReplicaTestCase
{
    /**
     * Get paramentes
     */
    public function testGetParameters()
    {
        $macro = new Replica_Macro_ThumbnailFit($width = 10, $height = 15);
        $expected = array(
            'maxWidth'  => $width,
            'maxHeight' => $height,
        );
        $this->assertEquals($expected, $macro->getParameters());
    }


    /**
     * Thumbnail
     */
    public function testThumbnail()
    {
        $plan = array(
            'FitWidthAndHeight' => array('png_120x90', 40, 30, 40, 30),
            'FitWidth'          => array('png_120x90', 80, 80, 80, 60),
            'FitHeight'         => array('png_90x120', 80, 80, 60, 80),
        );

        $image = new Replica_Image_Gd;
        foreach ($plan as $testName => $item) {
            list($file, $newWith, $newHeight, $expectedWidth, $expectedHeight) = $item;

            $image->loadFromFile($this->getFileNameInput($file));

            $macro = new Replica_Macro_ThumbnailFit($newWith, $newHeight);
            $macro->run($image);

            $this->assertImage($image, $expectedWidth, $expectedHeight, 'image/png', $testName);
            // Save and compare with template
            $fixtureName = 'ThumbnailFitTest_' . $testName;
            $image->saveAs($path = $this->getFileNameActual($fixtureName));
            $this->assertImageFile($this->getFileNameExpected($fixtureName), $path, $testName);
        }
    }


    /**
     * No changes if too small
     */
    public function testNoChangesIfTooSmall()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $macro = new Replica_Macro_ThumbnailFit($newWith = 300, $newHeight = 300);
        $macro->run($image);

        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameInput('png_120x90'), $path);
    }

}
