<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_GetSimplePagesForLoopTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = true;
    
    public function testGetSimplePagesForLoop()
    {
        $pages = $this->_addTestPages(1,10);    
        
        $this->dispatch('simple-pages/index/browse');
        
        $pages = get_simple_pages_for_loop();
        $this->assertEquals(11, count($pages));
        $slugs = array('about');
        for($i=1; $i<=10; $i++) {
            $slugs[] = 'testpage' . $i;
        }
        foreach($pages as $page) {
            $this->assertTrue(in_array($page->slug, $slugs));
        }
    }
}