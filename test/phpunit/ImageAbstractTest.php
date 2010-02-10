<?php
require_once dirname(__FILE__).'/../bootstrap.php';


class Replica_ImageAbstractTest extends ReplicaTestCase
{
    /**
     * Set type
     */
    public function testSetType()
    {
        $image = new Replica_Image_Gd;

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
        $image = new Replica_Image_Gd;

        $this->setExpectedException('Replica_Exception', 'Unknown image type');
        $image->setType('Unknown type');
    }


    /**
     * Get resource exception
     */
    public function testGetResourceException()
    {
        $image = new Replica_Image_Gd;
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

}
