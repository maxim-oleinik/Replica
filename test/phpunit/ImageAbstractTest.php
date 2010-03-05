<?php
/**
 * Image: abstraction test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
require_once dirname(__FILE__).'/../bootstrap.php';


/**
 * Test image
 */
class Replica_ImageAbstractTest_Image extends Replica_Image_Abstract
{
    public $log;

    public function loadFromFile($filePath)
    {
        $this->log = "loadFromFile({$filePath})";
    }

    public function loadFromString($data, $type = 'image/png')
    {
        $this->log = "loadFromString(,{$type})";
    }

    public function resize($width, $height) {}
    public function crop($x, $y, $width, $height) {}
    public function overlay($x, $y, Replica_Image_Abstract $image) {}
    public function saveAs($fullName, $mimeType = null) {}
    protected function _doReset() {}
}


/**
 * Test
 */
class Replica_ImageAbstractTest extends ReplicaTestCase
{
    /**
     * Create image from file
     */
    public function testCreateImageFromFile()
    {
        $image = new Replica_ImageAbstractTest_Image($path = '/some/path');
        $this->assertEquals("loadFromFile({$path})", $image->log, 'Expected image was loaded from FILE');
    }


    /**
     * Create image from string
     */
    public function testCreateImageFromString()
    {
        $image = new Replica_ImageAbstractTest_Image($data = 'image bin', $type = 'image/png');
        $this->assertEquals("loadFromString(,{$type})", $image->log, 'Expected image was loaded from STRING');
    }


    /**
     * Set type
     */
    public function testSetType()
    {
        $image = new Replica_ImageAbstractTest_Image;

        $types = array(
            'image/png',
            'image/gif',
            'image/jpeg',
        );

        foreach ($types as $type) {
            $image->setType($type);
            $this->assertEquals($type, $image->getType(), "Expected type `{$type}`");
        }
    }


    /**
     * Set type exception
     */
    public function testSetTypeException()
    {
        $image = new Replica_ImageAbstractTest_Image;

        $this->setExpectedException('Replica_Exception', 'Unknown image type');
        $image->setType('Unknown type');
    }


    /**
     * Get resource exception
     */
    public function testGetResourceException()
    {
        $image = new Replica_ImageAbstractTest_Image;
        $this->assertFalse($image->isInitialized());

        $this->setExpectedException('Replica_Exception_ImageNotInitialized');
        $image->getResource();
    }


    /**
     * Reset image
     */
    public function testResetImage()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('gif_16x14'));
        $this->assertTrue($image->isInitialized(), "Expected image IS initialized");

        $image->reset();
        $this->assertFalse($image->isInitialized(), "Expected image is NOT initialized");
        $this->assertImage($image, null, null, null, 'Reset image');
    }


    /**
     * Scale
     *
     * @dataProvider scaleTestPlan
     */
    public function testScale($fileName, $newWith, $newHeight, $expectedWidth, $expectedHeight)
    {
        $image = $this->getMock('Replica_Image_Gd', array('resize'));
        $image->expects($this->once())
              ->method('resize')
              ->with($expectedWidth, $expectedHeight);

        $image->loadFromFile($this->getFileNameInput($fileName));
        $image->scale($newWith, $newHeight);
    }

    /**
     * Scale test plan
     */
    public function scaleTestPlan()
    {
        return array(
            'FitWidthAndHeight'       => array('png_120x90', 40, 30, 40, 30),
            'FitWidthAndHeightExpand' => array('png_120x90', 240, 180, 240, 180),
            'FitWidth'                => array('png_120x90', 80, 80, 80, 60),
            'FitWidth2'               => array('png_120x90', 80, null, 80, 60),
            'FitWidthExpand'          => array('png_120x90', 240, null, 240, 180),
            'FitHeight'               => array('png_90x120', 80, 80, 60, 80),
            'FitHeight2'              => array('png_90x120', null, 80, 60, 80),
            'FitHeightExpand'         => array('png_90x120', null, 240, 180, 240),
        );
    }


    /**
     * Scale exception no dimensions
     */
    public function testScaleExceptionIfNoDimensions()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $this->setExpectedException('InvalidArgumentException', 'Expected width and/or height');
        $image->scale();
    }

}
