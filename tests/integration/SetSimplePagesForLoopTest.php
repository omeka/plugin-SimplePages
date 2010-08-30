<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_SetSimplePagesForLoopTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = true;
    
    public function testSetSimplePagesForLoop()
    {
        $this->dispatch('simple-pages/index/browse');
        $pages = get_simple_pages_for_loop();
        $this->assertEquals(1, count($pages));
        $page = $pages[0];
        $this->assertEquals('about', $page->slug);
        
        $pages = $this->_addTestPages(1,10);    
        set_simple_pages_for_loop($pages);
        $this->assertEquals($pages, get_simple_pages_for_loop());    
    }
}