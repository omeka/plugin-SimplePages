<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_HasSimplePagesForLoopTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = true;
    
    public function testHasSimplePagesForLoop()
    {
        $this->dispatch('simple-pages/index/browse');
        $this->assertTrue(has_simple_pages_for_loop());
    }
    
    public function testHasNoSimplePagesForLoop()
    {
        $this->_deleteAllPages();
        $this->dispatch('simple-pages/index/browse');
        $this->assertFalse(has_simple_pages_for_loop());
    }
    
    public function testHasNoSimplePagesForLoopAfterPagesForLoopAreReset()
    {
        $this->dispatch('simple-pages/index/browse');
        $this->assertTrue(has_simple_pages_for_loop());
        
        $pages = array();
        set_simple_pages_for_loop($pages);
        $this->assertFalse(has_simple_pages_for_loop());
    }
    
    public function testHasSimplePagesForLoopAfterPagesForLoopAreReset()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        foreach($pages as $page) {
            $page->delete();
        }
        $this->dispatch('simple-pages/index/browse');
        $this->assertFalse(has_simple_pages_for_loop());
        
        $pages = $this->_addTestPages(1,10);
        set_simple_pages_for_loop($pages);
        
        $this->assertTrue(has_simple_pages_for_loop());
    }
}