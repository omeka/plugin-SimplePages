<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_TotalSimplePagesTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = true;
    
    public function testTotalSimplePages()
    {
        $this->dispatch('simple-pages/index/browse');
        $this->assertEquals(1, total_simple_pages());
        
        $pages = $this->_addTestPages(1,10);
        $this->assertEquals(11, total_simple_pages());
        
        foreach($pages as $page) {
            $page->public = '0';
            $page->save();
        }
        $this->assertEquals(11, total_simple_pages());
        
        $this->_deleteAllPages();
        $this->assertEquals(0, total_simple_pages());
    }
}