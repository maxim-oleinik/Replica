<?php

define('REPLICA_DIR',      realpath(dirname(__FILE__).'/../'));
define('REPLICA_DIR_TEST', REPLICA_DIR.'/test');
define('REPLICA_DIR_LIB',  REPLICA_DIR.'/lib');

// Test lib
require REPLICA_DIR_TEST . '/ReplicaTestCase.php';

// Lib
require REPLICA_DIR . '/include.php';
