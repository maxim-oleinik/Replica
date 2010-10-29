<?php
/**
 * ImageProxy_Abstract test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
require_once dirname(__FILE__).'/../bootstrap.php';


/**
 * Test proxy
 */
class Replica_ImageProxy_AbstractTest_Image extends Replica_ImageProxy_Abstract
{
    public function getUid() {}
    protected function _loadImage(Replica_Image_Abstract $image) {}
}


/**
 * Test
 */
class Replica_ImageProxy_AbstractTest extends ReplicaTestCase
{
    /**
     * Default mime type
     */
    public function testDefaultMimeType()
    {
        $proxy = new Replica_ImageProxy_AbstractTest_Image;
        $this->assertEquals('image/png', $proxy->getMimeType());
    }


    /**
     * Set mime type
     */
    public function testSetMimeType()
    {
        $proxy = new Replica_ImageProxy_AbstractTest_Image;
        $proxy->setMimeType($type = 'image/gif');

        $this->assertEquals($type, $proxy->getMimeType());
        $this->assertEquals($type, $proxy->getImage()->getMimeType());
    }


    /**
     * Set quality
     */
    public function testSetQuality()
    {
        $proxy = new Replica_ImageProxy_AbstractTest_Image;
        $proxy->setQuality($q = 90);

        $this->assertEquals($q, $proxy->getQuality());
        $this->assertEquals($q, $proxy->getImage()->getQuality());
    }

}
