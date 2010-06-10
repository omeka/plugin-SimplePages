<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class Globals_SimplePagesSimplePageTest extends SimplePages_Test_AppTestCase
{       
    public function testSimplePage()
    {
        $this->_deleteAllPages(); 
        $this->_addTestPage('Test Title', 'testslug', 'testtext' );
        
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
    }
}