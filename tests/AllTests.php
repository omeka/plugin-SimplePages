<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 */

require_once 'SimplePages_Test_AppTestCase.php';

/**
 * Test suite for SimplePages.
 *
 * @package Omeka
 * @copyright Center for History and New Media, 2007-2010
 */
class SimplePages_AllTests extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new SimplePages_AllTests('SimplePages Tests');
        $testCollector = new PHPUnit_Runner_IncludePathTestCollector(
          array(dirname(__FILE__) . '/integration')
        );
        $suite->addTestFiles($testCollector->collectTests());
        return $suite;
    }
}
