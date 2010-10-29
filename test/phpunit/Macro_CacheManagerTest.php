<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * Macro: cache manager test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_Macro_CacheManagerTest extends ReplicaTestCase
{
    private $manager;


    /**
     * SetUp
     */
    protected function _setup()
    {
        $this->manager = new Replica_Macro_CacheManager($this->_dirActual);
    }


    /**
     * Run macro and cache result
     */
    public function testRunMacroAndCache()
    {
        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));

        $macro = $this->getMock('Replica_Macro_ThumbnailFit', array('run'), array(10, 20));
        $macro->expects($this->once())
              ->method('run');
        Replica::setMacro('macro', $macro);

        // Run and cache
        $path = $this->manager->get('macro', $imageProxy);

        $file = $this->_dirActual . '/' . $path;
        $this->assertRegExp("/\.png$/", $path);
        $this->assertImageFile($this->getFileNameInput('png_120x90'), $file);

        // From cache
        $this->assertEquals($path, $this->manager->get('macro', $imageProxy));
    }


    /**
     * File exension
     */
    public function testFileExtension()
    {
        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));
        $macro = new Replica_Macro_Null;

        $types = array(
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/jpeg' => 'jpg',
        );

        foreach ($types as $type => $extension) {
            $imageProxy->setMimeType($type);
            $path = $this->manager->get($macro, $imageProxy);
            $file = $this->_dirActual . '/' . $path;

            $image = new Replica_Image_Gd;
            $image->loadFromFile($file);

            $this->assertImage($image, 120, 90, $type, $type);
            $this->assertRegExp("/\.{$extension}$/", $path, $type);
        }
    }


    /**
     * Parameters invalidate cache
     */
    public function testParametersInvalidateCache()
    {
        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));

        $pathA = $this->manager->get(new Replica_Macro_ThumbnailFit(10, 20), $imageProxy);
        $pathB = $this->manager->get(new Replica_Macro_ThumbnailFit(15, 25), $imageProxy);

        $this->assertNotEquals($pathA, $pathB);
    }


    /**
     * Quality invalidates cache
     */
    public function testQualityInvalidateCache()
    {
        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));
        $imageProxy->setQuality(10);
        $pathA = $this->manager->get(new Replica_Macro_Null, $imageProxy);

        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));
        $imageProxy->setQuality(20);
        $pathB = $this->manager->get(new Replica_Macro_Null, $imageProxy);

        $this->assertNotEquals($pathA, $pathB);
    }


    /**
     * Failed mime type
     */
    public function testFailedMimeType()
    {
        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));
        $imageProxy->setMimeType('unknown type');
        $macro = new Replica_Macro_Null;

        $this->setExpectedException('Replica_Exception', 'Unknown image type');
        $path = $this->manager->get($macro, $imageProxy);
    }


    /**
     * Save dir not defined
     */
    public function testNoSaveDir()
    {
        $this->setExpectedException('InvalidArgumentException', 'Expected save dir');
        $this->manager = new Replica_Macro_CacheManager('');
    }


    /**
     * Failed to create save dir
     */
    public function testFailedToCreateSaveDir()
    {
        $dir = $this->_dirActual . '/' . $this->getName();
        mkdir($dir, 0400);
        $this->manager = new Replica_Macro_CacheManager($dir);

        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));
        $macro = new Replica_Macro_Null;

        $this->setExpectedException('Replica_Exception', 'Failed to create directory');
        $this->manager->get($macro, $imageProxy);
    }


}
