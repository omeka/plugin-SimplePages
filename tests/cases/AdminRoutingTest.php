<?php
/**
 * @copyright Center for History and New Media, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

class SimplePages_AdminRoutingTest extends SimplePages_Test_AppTestCase
{
    public function testRoutesDoNotApply()
    {
        $page = $this->_addTestPage('Test', 'items');        
        $this->_reloadRoutes();

        $this->dispatch('/items');
        $this->assertNotModule('simple-pages');
        
        set_option('simple_pages_home_page_id', $page->id);
        $this->dispatch('/');
        $this->assertNotModule('simple-pages');
    }
}
