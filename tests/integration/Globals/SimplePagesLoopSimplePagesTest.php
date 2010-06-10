<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class Globals_SimplePagesLoopSimplePagesTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = true;
    
    public function testLoopSimplePages()
    {
        $pages = $this->_addTestPages(1,10);
        $slugs = array('about');
        foreach($pages as $page) {
            $slugs[] = $page->slug;
        }
        $this->dispatch('simple-pages/index/browse');
        
        $count = 0;
        while(loop_simple_pages()) {
            $count++;
            $page = get_current_simple_page();
            $this->assertTrue(in_array($page->slug, $slugs));
        }
        $this->assertEquals(11, $count);
    }
}