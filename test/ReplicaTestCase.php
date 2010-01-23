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

}
