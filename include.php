<?php

$libDir = dirname(__FILE__) . '/lib';

// Lib
require_once $libDir . '/Replica.php';
require_once $libDir . '/Exception.php';
require_once $libDir . '/ImageAbstract.php';
require_once $libDir . '/ImageGd.php';

require_once $libDir . '/ImageProxy.php';
require_once $libDir . '/ImageProxy_FromFile.php';
require_once $libDir . '/Macro_CacheManager.php';

require_once $libDir . '/Macro_Interface.php';
require_once $libDir . '/Macro_ThumbnailFit.php';
require_once $libDir . '/Macro_ThumbnailFixed.php';
require_once $libDir . '/Macro_Fake.php';
