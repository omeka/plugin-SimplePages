<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_GetCurrentSimplePageTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = false;
    
    public function testGetCurrentSimplePage()
    {
        $this->dispatch('about');
        $page = get_current_simple_page();
        $this->assertEquals('about', $page->slug);
    }
}
