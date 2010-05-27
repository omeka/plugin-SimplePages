<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class Globals_SimplePagesGetPageLinksTest extends SimplePages_Test_AppTestCase
{       
    public function testGetPageLinksWithNoLinks()
    {        
       $this->_deleteAllPages();
    
       $this->dispatch('/');
    
       $navLinks = array();
       $this->assertEquals($navLinks, simple_pages_get_page_links());
    }
    
    public function testGetPageLinksWithOneMainLinks()
    {        
       $this->dispatch('/');
       
       $navLinks = array('About' => '/about');
       $this->assertEquals($navLinks, simple_pages_get_page_links());
    }
     
    public function testGetPageLinksWithMultipleMainLinksAndNoSublinks()
    {   
        $testPageCount = 17;
        $this->_addTestPages(1, $testPageCount);
    
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals($testPageCount + 1, count($pages));
        
        $this->dispatch('/');
   
        $navLinks = array('About' => '/about');
        for($i = 1; $i <= $testPageCount; $i++) {
           $navLinks['Test Page ' . $i] = '/testpage' . $i;
        }
       
        $this->assertEquals($navLinks, simple_pages_get_page_links());
    }
    
    protected function _deleteAllPages()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        foreach($pages as $page) {
            $page->delete();
        }
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals(0, count($pages), 'There should be no pages.');
    }
    
    protected function _addTestPages($minIndex=0, $maxIndex=0)
    {
        for($i = $minIndex; $i <= $maxIndex; $i++) {
            $page = new SimplePagesPage;
            $page->title = 'Test Page ' . $i;
            $page->slug = 'testpage' . $i;
            $page->created_by_user_id = $this->user->id;
            $page->is_published = 1;
            $page->add_to_public_nav = 1;
            $page->text = 'asdf' . $i;
            $page->save();
        }        
    }
}