<?php
require_once dirname(__FILE__).'/../bootstrap.php';


class Replica_ImageGd_TransformTest extends ReplicaTestCase
{
    /**
     * Test: Resize image
     */
    public function testResize()
    {
        $image = new Replica_ImageGd;
        $image->loadFromFile($this->getImgPath('jpg_200x400'));

        $image->resize(10, 10);
        $this->assertImageInfo($image, 10, 10, 'image/jpeg', 'Image');
        $this->assertImageGdSize($image->getResource(), 10, 10, 'Resource');
    }


    /**
     * Test: Resize empty image
     */
    public function testResizeEmptyImage()
    {
        $image = new Replica_ImageGd;

        $this->setExpectedException('Replica_Exception');
        $image->resize(10, 10);
    }

}
