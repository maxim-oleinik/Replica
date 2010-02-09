<?php

$libDir = dirname(__FILE__) . '/lib';

// Lib
require_once $libDir . '/Replica.php';
require_once $libDir . '/Exception.php';
require_once $libDir . '/Image_Abstract.php';
require_once $libDir . '/Image_Gd.php';

require_once $libDir . '/ImageProxy_Abstract.php';
require_once $libDir . '/ImageProxy_FromFile.php';
require_once $libDir . '/Macro_CacheManager.php';

require_once $libDir . '/Macro_Interface.php';
require_once $libDir . '/Macro_ThumbnailFit.php';
require_once $libDir . '/Macro_ThumbnailFixed.php';
require_once $libDir . '/Macro_Null.php';
