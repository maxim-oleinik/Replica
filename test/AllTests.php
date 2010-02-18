<?php
require_once(dirname(__FILE__) . '/bootstrap.php');

/**
 * All lib tests
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_AllTests extends PHPUnit_Framework_TestSuite
{
    /**
     * TestSuite
     */
    public static function suite()
    {
        $runner = new PHPUnit_TextUI_TestRunner(new PHPUnit_Runner_StandardTestSuiteLoader);
        $suite = $runner->getTest(dirname(__FILE__).'/phpunit');
        $suite->setName('Replica');
        return $suite;
    }

}
