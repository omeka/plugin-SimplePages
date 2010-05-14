<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class Globals_SimplePagesIsHomePageTest extends SimplePages_Test_AppTestCase
{   
    public function testIsHomePage()
    {   
        $page = new SimplePagesPage;
        $page->title = 'Test Page';
        $page->slug = 'testpage';
        $page->created_by_user_id = $this->user->id;
        $page->is_published = 1;
        $page->text = '';
        $page->save();
        
        $this->assertFalse(simple_pages_is_home_page($page));
        
        set_option('simple_pages_home_page_id', $page->id);
        
        $this->assertTrue(simple_pages_is_home_page($page));
    }
}