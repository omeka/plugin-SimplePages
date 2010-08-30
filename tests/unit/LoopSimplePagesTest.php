<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SimplePages
 **/

// This is an example of why deeply nested test files can get confusing.
// This test must include all the global helper functions as that job would
// normally be handled by Omeka_Test_AppTestCase.
// The number of '/../' makes it more difficult to understand where the file actually is.
require_once dirname(__FILE__) . '/../../../../application/helpers/all.php';

// Despite it seeming a bit ugly and verbose to do all these includes by hand,
// it gives a much more accurate sense of all the dependencies that are required
// for this one function to work.  The obvious advantage to this is that a new
// developer could start work on the project and know immediately where to look
// to cross-reference existing functions that are used by this one.
require_once dirname(__FILE__) . '/../../helpers/SimplePageFunctions.php';

// We also need this because the signature of set_current_simple_page() requires 
// the SimplePagesPage model.
require_once dirname(__FILE__) . '/../../models/SimplePagesPage.php';

/**
 * Notice how this class does not extend off Omeka_Test_AppTestCase.  This means
 * that the entire application will not be bootstrapped for each test run.
 * 
 * Aside from the obvious performance gains from skipping the bootstrap, there
 * is less of a yucky inheritance problem to deal with when writing tests.
 * 
 * While you ponder what that means, check out the following links on why inheritance
 * is sometimes a bad idea:
 * 
 * https://c2.com/cgi/wiki?ImplementationInheritanceIsEvil
 * http://www.javaworld.com/javaworld/jw-08-2003/jw-0801-toolbox.html
 * And one response to it:
 * http://beust.com/weblog2/archives/000004.html
 * 
 * Best quote from these articles btw:
 * "The problem with inheritance is that if your parents are unattractive, the same thing happens to you."
 *
 * In a nutshell, this is because the deeper your inheritance hierarchy, the 
 * more potential places your code could self-destruct if the parent behavior
 * changes.
 * 
 * Concrete example:
 *   
 * Omeka_Test_AppTestCase extends Zend_Test_ControllerTestCase, which in turn
 * extends PHPUnit_Framework_TestCase.  Three deep is already pushing the limit
 * of acceptable inheritance.  This is mostly because both PHPUnit_Framework_TestCase
 * is extremely stable and not likely to change implementations drastically.
 * The Zend component extends this class to make it easier to test controllers.
 * So already a Zend Framework upgrade (say, from 1.9 to 1.10) could break
 * every test written in Omeka.  Likewise any significant change to Omeka_Test_AppTestCase
 * would break every test that uses that class.  Etc. etc. all the way down.
 * 
 * @package SimplePages
 * @copyright Center for History and New Media, 2007-2010
 * 
 * Note the avoidance of a complicated class name.  We can do this because the
 * test does not load every single class in Omeka, so there is far less of a chance
 * of a naming collision.  Not to mention the fact that it still contains "SimplePages"
 * in the class name.  The previous class name was "Globals_SimplePagesLoopSimplePagesTest",
 * which is a bit too wtf-y. Keep It Simple.
 **/
class LoopSimplePagesTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // loop_records() needs access to the view object, as you will discover
        // if you run these tests without this line.
        // This is not a real 'unit' test because Omeka_View has not been 
        // mocked out, but for what we need for this test, this is much easier.
        $this->view = new Omeka_View;
        Zend_Registry::set('view', $this->view);    
        
        $this->dbAdapter = new Zend_Test_DbAdapter;
        $this->db = new Omeka_Db($this->dbAdapter);    
    }
    
    // This test, which would have taken 4 seconds to run if using Omeka_Test_AppTestCase,
    // now takes 0 seconds to run.  Which is great because it doesn't
    // really do anything except test the base case.
    public function testDoesNothingIfNoPages()
    {
        while (loop_simple_pages()) {
            $this->fail("If it gets here, loop_simple_pages() must be broken.");
        }
    }
    
    public function testIteratesThroughPagesIfExist()
    {
        // Notice also how there is no need to interact with the database in these
        // tests.  This is because loop_simple_pages() needs no database access
        // at all, making it a very simple function to test.  The previous
        // test implementation inserted 10 pages into the database and then 
        // called loop_simple_pages() on those same objects which had then been
        // retrieved from the database.  Lots of extra CPU cycles to test a simple 
        // iterator.
        $pageCount = 5;
        $pages = array();
        for($i = 1; $i <= $pageCount; $i++) {
            $page = new SimplePagesPage($this->db);
            $page->slug = $i;
            $pages[] = $page;
        }
        
        // More evidence that this is not a unit test: we are assuming that 
        // set_simple_pages_for_loop() works correctly.  This means the test
        // could fail if this function ceases to work correctly, though the hope
        // is that there is a separate test for set_simple_pages_for_loop()
        // which would also fail, albeit with a more useful level of detail.
        set_simple_pages_for_loop($pages);
        
        $count = 0;
        while (loop_simple_pages()) {
            $this->assertEquals(get_current_simple_page()->slug, $count + 1);
            $count++;
        }
        $this->assertEquals($pageCount, $count, "loop_simple_pages() did not iterate through all of the given objects.");
    }
}
