<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_HasSimplePagesTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = true;
    
    public function testHasSimplePages()
    {
        $this->dispatch('simple-pages/index/browse');
        $this->assertTrue(has_simple_pages());
    }
    
    public function testHasNoSimplePages()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        foreach($pages as $page) {
            $page->delete();
        }
        $this->dispatch('simple-pages/index/browse');
        $this->assertFalse(has_simple_pages());
    }
}