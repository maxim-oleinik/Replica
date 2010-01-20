<?php

class ReplicaTestCase extends PHPUnit_Framework_TestCase
{
    protected $dirData;


    /**
     * Construct
     */
    public function __construct()
    {
        $this->dirData   = REPLICA_DIR_TEST . '/data';
    }


    /**
     * Get data image path by name
     *
     * @param  string $fileName
     * @return string
     */
    public function getImgPath($fileName)
    {
        return  $this->dirData . '/' . $fileName;
    }


    /**
     * Assert image info
     */
    public function assertImageInfo(Replica_ImageGd $image, $width, $height, $type = 'image/png', $message = null)
    {
        $this->assertEquals($width,  $image->getWidth(),  $message.': Width');
        $this->assertEquals($height, $image->getHeight(), $message.': Height');
        $this->assertEquals($type,   $image->getType(),   $message.': Type');
    }


    /**
     * Assert GD resource size
     */
    public function assertImageGdSize($img, $width, $height, $message = null)
    {
        $this->assertEquals(imagesx($img), $width,  $message.' Width');
        $this->assertEquals(imagesy($img), $height, $message.' Height');
    }

}
