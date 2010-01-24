<?php

class ReplicaTestCase extends PHPUnit_Framework_TestCase
{
    private
        $_dirInput,
        $_dirExpected,
        $_dirActual;


    /**
     * Construct
     */
    public function __construct()
    {
        $this->_dirInput    = REPLICA_DIR_TEST . '/fixtures/input';
        $this->_dirExpected = REPLICA_DIR_TEST . '/fixtures/expected';
        $this->_dirActual   = REPLICA_DIR_TEST . '/fixtures/actual';
    }


    /**
     * SetUp
     */
    final public function setUp()
    {
        `rm -f {$this->_dirActual}/*`;

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
     * Assert image info
     */
    public function assertImageInfo(Replica_ImageGd $image, $width, $height, $type = 'image/png', $message = null)
    {
        $message = $message ? $message.': ' : null;

        $this->assertEquals($width,  $image->getWidth(),  $message.'Meta (width)');
        $this->assertEquals($height, $image->getHeight(), $message.'Meta (height)');
        $this->assertEquals($type,   $image->getType(),   $message.'Meta (type)');
    }


    /**
     * Assert GD resource size
     */
    public function assertImageGdSize($img, $width, $height, $message = null)
    {
        $message = $message ? ': '.$message : null;

        $this->assertEquals(imagesx($img), $width,  $message.'Resourse (width)');
        $this->assertEquals(imagesy($img), $height, $message.'Resourse (height)');
    }


    /**
     * Assert image
     */
    public function assertImage(Replica_ImageGd $image, $width, $height, $type = 'image/png', $message = null)
    {
        $this->assertImageInfo($image, $width, $height, $type, $message);
        $this->assertImageGdSize($image->getResource(), $width, $height, $message);
    }


    /**
     * Assert image file
     */
    public function assertImageFile($expected, $actual, $message = null)
    {
        if ($expected instanceof Replica_ImageGd) {
            $image = new Replica_ImageGd;
            $image->loadFromFile($actual);
            $this->assertImage($image, $expected->getWidth(), $expected->getHeight(), $expected->getType(), $message);
        } else {
            $this->assertEquals(md5_file($expected), md5_file($actual), $message);
        }
    }

}
