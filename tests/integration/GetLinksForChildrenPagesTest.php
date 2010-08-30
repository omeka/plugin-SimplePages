<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_GetLinksForChildrenPagesTest extends SimplePages_Test_AppTestCase
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
        $testPageCount = 8;
        $this->_addTestPages(1, $testPageCount);
    
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals($testPageCount + 1, count($pages));
        
        $this->dispatch('/');
   
        $expectedNavLinks = array('About' => '/about');
        for($i = 1; $i <= $testPageCount; $i++) {
           $expectedNavLinks['Test Page ' . $i] = '/testpage' . $i;
        }
       
        $this->assertEquals($expectedNavLinks, simple_pages_get_links_for_children_pages());
    }
    
    public function testGetPageLinksForPublishedPages()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals(1, count($pages));
        $aboutPage = $pages[0];
                
        $testPage1 = $this->_addTestPage('Test Title 1', 'testslug1', 'testtext1');
        $testPage1->parent_id = $aboutPage->id;
        $testPage1->order = 1;
        $testPage1->is_published = 1;
        $testPage1->save();

        $testPage2 = $this->_addTestPage('Test Title 2', 'testslug2', 'testtext2');
        $testPage2->parent_id = $aboutPage->id;
        $testPage2->order = 2;
        $testPage2->is_published = 1;
        $testPage2->save();

        $testPage3 = $this->_addTestPage('Test Title 3', 'testslug3', 'testtext3');
        $testPage3->parent_id = $aboutPage->id;
        $testPage3->order = 3;
        $testPage3->is_published = 0;
        $testPage3->save();
        
        $testPage4 = $this->_addTestPage('Test Title 4', 'testslug4', 'testtext4');
        $testPage4->parent_id = $testPage2->id;
        $testPage4->order = 1;
        $testPage4->is_published = 0;
        $testPage4->save();
        
        $testPage5 = $this->_addTestPage('Test Title 5', 'testslug5', 'testtext5');
        $testPage5->parent_id = $testPage2->id;
        $testPage5->order = 2;
        $testPage5->is_published = 1;
        $testPage5->save();
        
        $this->dispatch('/');
        
        $actualNavLinks = simple_pages_get_links_for_children_pages($aboutPage->id, null, 'order', true, false);
        $this->assertEquals(2, count($actualNavLinks));
        $expectedNavLinks = array();
        $expectedNavLinks['Test Title 1'] = '/testslug1';
        $expectedNavLinks['Test Title 2'] = array(
                                                'uri' => '/testslug2', 
                                                'subnav_links' => array('Test Title 5' => '/testslug5'),
                                                'subnav_attributes' => array('class' => 'subnav-1')
                                            );
        
        $this->assertEquals($expectedNavLinks, $actualNavLinks);
        
    }
    
    public function testGetPageLinksForAddToPublicNavPages()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals(1, count($pages));
        $aboutPage = $pages[0];
                
        $testPage1 = $this->_addTestPage('Test Title 1', 'testslug1', 'testtext1');
        $testPage1->parent_id = $aboutPage->id;
        $testPage1->order = 1;
        $testPage1->is_published = 1;
        $testPage1->add_to_public_nav = 0;
        $testPage1->save();

        $testPage2 = $this->_addTestPage('Test Title 2', 'testslug2', 'testtext2');
        $testPage2->parent_id = $aboutPage->id;
        $testPage2->order = 2;
        $testPage2->is_published = 1;
        $testPage2->add_to_public_nav = 1;
        $testPage2->save();

        $testPage3 = $this->_addTestPage('Test Title 3', 'testslug3', 'testtext3');
        $testPage3->parent_id = $aboutPage->id;
        $testPage3->order = 3;
        $testPage3->is_published = 0;
        $testPage3->add_to_public_nav = 1;
        $testPage3->save();
        
        $testPage4 = $this->_addTestPage('Test Title 4', 'testslug4', 'testtext4');
        $testPage4->parent_id = $testPage2->id;
        $testPage4->order = 1;
        $testPage4->is_published = 0;
        $testPage4->add_to_public_nav = 1;
        $testPage4->save();
        
        $testPage5 = $this->_addTestPage('Test Title 5', 'testslug5', 'testtext5');
        $testPage5->parent_id = $testPage2->id;
        $testPage5->order = 2;
        $testPage5->is_published = 1;
        $testPage5->add_to_public_nav = 0;
        $testPage5->save();
        
        $this->dispatch('/');
        
        $actualNavLinks = simple_pages_get_links_for_children_pages($aboutPage->id, null, 'order', false, true);
        $this->assertEquals(2, count($actualNavLinks));
        $expectedNavLinks = array();
        $expectedNavLinks['Test Title 2'] = array(
                                                'uri' => '/testslug2', 
                                                'subnav_links' => array('Test Title 4' => '/testslug4'),
                                                'subnav_attributes' => array('class' => 'subnav-1')
                                            );
        $expectedNavLinks['Test Title 3'] = '/testslug3';
        
        $this->assertEquals($expectedNavLinks, $actualNavLinks);
    }
}