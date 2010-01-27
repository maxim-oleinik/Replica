<?php
require_once dirname(__FILE__).'/../bootstrap.php';


class Replica_Macro_CacheTest extends ReplicaTestCase
{
    /**
     * SetUp
     */
    protected function _setup()
    {
        Replica_Macro_Cache::setDir($this->_dirActual);
    }


    /**
     * Run macro and cache
     */
    public function testRunMacroAndCache()
    {
        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));

        $macro = $this->getMock('Replica_Macro_Fake', array('run'));
        $macro->expects($this->once())
              ->method('run');
        Replica::setMacro('macro', $macro);

        // Run and cache
        $path = Replica_Macro_Cache::get('macro', $imageProxy);

        $file = $this->_dirActual . '/' . $path;
        $this->assertRegExp("/\.png$/", $path);
        $this->assertImageFile($this->getFileNameInput('png_120x90'), $file);

        // From cache
        $this->assertEquals($path, Replica_Macro_Cache::get('macro', $imageProxy));
    }


    /**
     * File exension
     */
    public function testFileExtension()
    {
        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));
        $macro = new Replica_Macro_Fake;

        $types = array(
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/jpeg' => 'jpg',
        );

        foreach ($types as $type => $extension) {
            $path = Replica_Macro_Cache::get($macro, $imageProxy, $type);
            $file = $this->_dirActual . '/' . $path;

            $image = new Replica_ImageGD;
            $image->loadFromFile($file);

            $this->assertImage($image, 120, 90, $type, $type);
            $this->assertRegExp("/\.{$extension}$/", $path, $type);
        }

    }


    /**
     * Save dir not defined
     */
    public function testNoSaveDir()
    {
        Replica_Macro_Cache::setDir(null);

        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));
        $macro = new Replica_Macro_Fake;

        $this->setExpectedException('Replica_Exception', 'Save dir not defined');
        Replica_Macro_Cache::get($macro, $imageProxy);
    }


    /**
     * Failed to create save dir
     */
    public function testFailedToCreateSaveDir()
    {
        $dir = $this->_dirActual . '/' . $this->getName();
        mkdir($dir, 0400);
        Replica_Macro_Cache::setDir($dir);

        $imageProxy = new Replica_ImageProxy_FromFile($this->getFileNameInput('png_120x90'));
        $macro = new Replica_Macro_Fake;

        $this->setExpectedException('Replica_Exception', 'Failed to create directory');
        Replica_Macro_Cache::get($macro, $imageProxy);
    }

}
