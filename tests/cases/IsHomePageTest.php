<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_IsHomePageTest extends SimplePages_Test_AppTestCase
{   
    public function testIsHomePage()
    {   
        $page = $this->_addTestPage();        
        $this->assertFalse(simple_pages_is_home_page($page));
        
        set_option('simple_pages_home_page_id', $page->id);
        $this->assertTrue(simple_pages_is_home_page($page));
    }
}