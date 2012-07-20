<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_SimplePageTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = false;
        
    public function testSimplePage()
    {
        $this->_deleteAllPages(); 
        $page = $this->_addTestPage('Test Title', 'testslug', 'testtext' );
        $this->_reloadRoutes();        
        $this->dispatch('testslug');
        
        $this->assertEquals('Test Title', simple_page('title'));
        $this->assertEquals('testtext', simple_page('text'));
        $this->assertEquals('testslug', simple_page('slug'));
        $this->assertEquals('1', simple_page('is_published'));
        $this->assertEquals('1', simple_page('add_to_public_nav'));
        $this->assertEquals($this->user->id, simple_page('created_by_user_id'));
        $this->assertEquals($this->user->id, simple_page('modified_by_user_id'));
        $this->assertEquals(0, simple_page('order'));
        $this->assertEquals(0, simple_page('parent_id'));
        
        
        $page2 = $this->_addTestPage('Test Title 2', 'testslug2', 'testtext2');
        $page2->parent_id = $page->id;
        $page2->save();
        
        $this->assertEquals('Test Title 2', simple_page('title', array(), $page2));
        $this->assertEquals('testtext2', simple_page('text', array(), $page2));
        $this->assertEquals('testslug2', simple_page('slug', array(), $page2));
        $this->assertEquals('1', simple_page('is_published', array(), $page2));
        $this->assertEquals('1', simple_page('add_to_public_nav', array(), $page2));
        $this->assertEquals($this->user->id, simple_page('created_by_user_id', array(), $page2));
        $this->assertEquals($this->user->id, simple_page('modified_by_user_id', array(), $page2));
        $this->assertEquals(0, simple_page('order', array(), $page2));
        $this->assertEquals($page->id, simple_page('parent_id', array(), $page2));
    }
}
