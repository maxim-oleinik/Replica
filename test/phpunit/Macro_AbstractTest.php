<?php
require_once dirname(__FILE__).'/../bootstrap.php';


class Replica_Macro_AbstractTest extends ReplicaTestCase
{
    /**
     * Image not initialized exception
     */
    public function testImageNotInitializedException()
    {
        $macro = new Replica_Macro_Null;

        $this->setExpectedException('Replica_Exception_ImageNotInitialized');
        $macro->run(new Replica_Image_Gd);
    }
}
