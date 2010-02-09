<?php
require_once dirname(__FILE__).'/../bootstrap.php';


class Replica_Replica_CacheManagerTest extends ReplicaTestCase
{
    /**
     * Set/Get cache manager
     */
    public function testSetGetCacheManager()
    {
        Replica::setCacheManager($manager = new Replica_Macro_CacheManager('/some/dir'));

        $this->assertType('Replica_Macro_CacheManager', Replica::cache());
        $this->assertSame($manager, Replica::cache());
    }


    /**
     * Exception if cache manager not defined
     */
    public function testExceptionIfNoCacheManager()
    {
        $this->setExpectedException('Replica_Exception', 'Cache manager not defined');
        Replica::cache();
    }

}
