<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class Globals_SimplePagesGetLinksForChildrenPagesTest extends SimplePages_Test_AppTestCase
{       
    public function testGetPageLinksWithNoLinks()
    {        
       $this->_deleteAllPages();
    
       $this->dispatch('/');
    
       $navLinks = array();
       $this->assertEquals($navLinks, simple_pages_get_links_for_children_pages());
    }
    
    public function testGetPageLinksWithOneMainLinks()
    {        
       $this->dispatch('/');
       
       $navLinks = array('About' => '/about');
       $this->assertEquals($navLinks, simple_pages_get_links_for_children_pages());
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
       
        $this->assertEquals($navLinks, simple_pages_get_links_for_children_pages());
    }
}