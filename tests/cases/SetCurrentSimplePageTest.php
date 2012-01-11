<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_SetCurrentSimplePageTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = false;
    
    public function testSetCurrentSimplePage()
    {
        $this->dispatch('about');
        $page = get_current_simple_page();
        $this->assertEquals('about', $page->slug);
        
        $page = $this->_addTestPage();    
        set_current_simple_page($page);
        
        $this->assertEquals($page, get_current_simple_page());    
    }
}
