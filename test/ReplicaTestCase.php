<?php

/**
 * Base lib TestCase
 *
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class ReplicaTestCase extends PHPUnit_Framework_TestCase
{
    protected $backupGlobals = false;
    protected $backupStaticAttributes = false;
    protected $preserveGlobalState = false;

    protected
        $_dirInput,
        $_dirExpected,
        $_dirActual;


    /**
     * SetUp
     */
    final public function setUp()
    {
        $this->_dirInput    = REPLICA_DIR_TEST . '/fixtures/input';
        $this->_dirExpected = REPLICA_DIR_TEST . '/fixtures/expected';
        $this->_dirActual   = REPLICA_DIR_TEST . '/fixtures/actual';

        `rm -rf {$this->_dirActual}; mkdir {$this->_dirActual}`;

        $this->_setup();
    }

    protected function _setUp()
    {
    }


    /**
     * TearDown
     */
    final public function tearDown()
    {
        $this->_teardown();

        Replica::removeAll();
        Replica::setCacheManager(null);
    }

    protected function _teardown()
    {
    }


    /**
     * Get INPUT image path by name
     *
     * @param  string $fileName
     * @return string
     */
    public function getFileNameInput($fileName)
    {
        return  $this->_dirInput . '/' . $fileName;
    }


    /**
     * Get EXPECTED image path by name
     *
     * @param  string $fileName
     * @return string
     */
    public function getFileNameExpected($fileName)
    {
        if (strpos($fileName, '::')) {
            $fileName = str_replace('::', '_', $fileName);
        }
        return  $this->_dirExpected . '/' . $fileName;
    }


    /**
     * Get ACTUAL image path by name
     *
     * @param  string $fileName
     * @return string
     */
    public function getFileNameActual($fileName)
    {
        if (strpos($fileName, '::')) {
            $fileName = str_replace('::', '_', $fileName);
        }
        return $this->_dirActual . '/' . $fileName;
    }


    /**
     * Assert image
     */
    public function assertImage($image, $width, $height, $type = 'image/png', $message = null)
    {
        $message = $message ? $message.': ' : null;

        if (!$image instanceof Replica_Image_Gd) {
            $path = (string) $image;
            $image = new Replica_Image_Gd;
            $this->assertTrue($image->loadFromFile($path), $message."Image is loaded from file `{$path}`");
        }

        // Meta
        $this->assertEquals($width,  $image->getWidth(),  $message.'Meta (width)');
        $this->assertEquals($height, $image->getHeight(), $message.'Meta (height)');
        $this->assertEquals($type,   $image->getType(),   $message.'Meta (type)');

        if (null !== $width &&  null !== $height) {
            $this->assertTrue($image->isInitialized(), $message.'Image is loaded');

            // GD
            $this->assertEquals(imagesx($image->getResource()), $width,  $message.'Resource (width)');
            $this->assertEquals(imagesy($image->getResource()), $height, $message.'Resource (height)');
        }
    }


    /**
     * Assert image file
     */
    public function assertImageFile($expected, $actual, $message = null)
    {
        if ($expected instanceof Replica_Image_Gd) {
            $image = new Replica_Image_Gd;
            $image->loadFromFile($actual);
            $this->assertImage($image, $expected->getWidth(), $expected->getHeight(), $expected->getType(), $message);
        } else {
            $this->assertEquals(md5_file($expected), md5_file($actual), $message);
        }
    }

}
