<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_NavigationTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = false;
        
    public function testSimplePagesNavigationForNoChildrenPages()
    {
        $this->dispatch('about');
        $this->assertEquals('', simple_pages_navigation(null));
    }
    
    public function testSimplePagesNavigationForSomeChildrenPages()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals(1, count($pages));
        $aboutPage = $pages[0];
                
        $testPage1 = $this->_addTestPage('Test Title 1', 'testslug1', 'testtext1');
        $testPage1->parent_id = $aboutPage->id;
        $testPage1->order = 1;
        $testPage1->save();
        
        $testPage2 = $this->_addTestPage('Test Title 2', 'testslug2', 'testtext2');
        $testPage2->parent_id = $aboutPage->id;
        $testPage2->order = 2;
        $testPage2->save();
        
        $testPage0 = $this->_addTestPage('Test Title 0', 'testslug0', 'testtext0');
        $testPage0->parent_id = $aboutPage->id;
        $testPage0->order = 0;
        $testPage0->save();

        $this->dispatch('about');
        
        $expectedNav = '';
        $expectedNav .= '<ul class="simple-pages-navigation">' . "\n";
        $expectedNav .= '<li class="nav-test-title-0"><a href="/testslug0">Test Title 0</a></li>' . "\n";
        $expectedNav .= '<li class="nav-test-title-1"><a href="/testslug1">Test Title 1</a></li>' . "\n";
        $expectedNav .= '<li class="nav-test-title-2"><a href="/testslug2">Test Title 2</a></li>' . "\n";
        $expectedNav .= '</ul>' . "\n";
        
        $this->assertEquals($expectedNav, simple_pages_navigation(null));
    }
    
    public function testSimplePagesNavigationForSomeGrandChildrenPages()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals(1, count($pages));
        $aboutPage = $pages[0];
                
        $testPage1 = $this->_addTestPage('Test Title 1', 'testslug1', 'testtext1');
        $testPage1->parent_id = $aboutPage->id;
        $testPage1->order = 1;
        $testPage1->save();
        
        $testPage2 = $this->_addTestPage('Test Title 2', 'testslug2', 'testtext2');
        $testPage2->parent_id = $aboutPage->id;
        $testPage2->order = 2;
        $testPage2->save();
        
        $testPage0 = $this->_addTestPage('Test Title 0', 'testslug0', 'testtext0');
        $testPage0->parent_id = $aboutPage->id;
        $testPage0->order = 0;
        $testPage0->save();
        
        $testPage4 = $this->_addTestPage('Test Title 4', 'testslug4', 'testtext4');
        $testPage4->parent_id = $testPage0->id;
        $testPage4->order = 1;
        $testPage4->save();
        
        $testPage5 = $this->_addTestPage('Test Title 5', 'testslug5', 'testtext5');
        $testPage5->parent_id = $testPage0->id;
        $testPage5->order = 0;
        $testPage5->save();
        
        $testPage6 = $this->_addTestPage('Test Title 6', 'testslug6', 'testtext6');
        $testPage6->parent_id = $testPage2->id;
        $testPage6->order = 0;
        $testPage6->save();
        
        $testPage7 = $this->_addTestPage('Test Title 7', 'testslug7', 'testtext7');
        $testPage7->parent_id = $testPage2->id;
        $testPage7->order = 1;
        $testPage7->save();
        
        $testPage8 = $this->_addTestPage('Test Title 8', 'testslug8', 'testtext8');
        $testPage8->parent_id = $testPage6->id;
        $testPage8->order = 0;
        $testPage8->save();

        $this->dispatch('about');
        
        $expectedNav = '';
        $expectedNav .= '<ul class="simple-pages-navigation">' . "\n";
        $expectedNav .= '<li class="nav-test-title-0"><a href="/testslug0">Test Title 0</a>' . "\n";
        $expectedNav .= '<ul class="subnav-1">' . "\n";
        $expectedNav .= '<li class="nav-test-title-5"><a href="/testslug5">Test Title 5</a></li>' . "\n";
        $expectedNav .= '<li class="nav-test-title-4"><a href="/testslug4">Test Title 4</a></li>' . "\n";
        $expectedNav .= '</ul>' . "\n";
        $expectedNav .= '</li>' . "\n";
        $expectedNav .= '<li class="nav-test-title-1"><a href="/testslug1">Test Title 1</a></li>' . "\n";
        $expectedNav .= '<li class="nav-test-title-2"><a href="/testslug2">Test Title 2</a>' . "\n";
        $expectedNav .= '<ul class="subnav-1">' . "\n";
        $expectedNav .= '<li class="nav-test-title-6"><a href="/testslug6">Test Title 6</a>' . "\n"; 
        $expectedNav .= '<ul class="subnav-2">' . "\n";
        $expectedNav .= '<li class="nav-test-title-8"><a href="/testslug8">Test Title 8</a></li>' . "\n";
        $expectedNav .= '</ul>' . "\n";
        $expectedNav .= '</li>' . "\n";
        $expectedNav .= '<li class="nav-test-title-7"><a href="/testslug7">Test Title 7</a></li>' . "\n";
        $expectedNav .= '</ul>' . "\n";
        $expectedNav .= '</li>' . "\n";
        $expectedNav .= '</ul>' . "\n";
        
        $this->assertEquals($expectedNav, simple_pages_navigation(null));
    }
    
    public function testSimplePagesNavigationForOnlyPublishedChildrenPages()
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
        $testPage2->is_published = 0;
        $testPage2->save();
        
        $testPage0 = $this->_addTestPage('Test Title 0', 'testslug0', 'testtext0');
        $testPage0->parent_id = $aboutPage->id;
        $testPage0->order = 0;
        $testPage0->is_published = 0;
        $testPage0->save();

        $this->dispatch('about');
        
        $expectedNav = '';
        $expectedNav .= '<ul class="simple-pages-navigation">' . "\n";
        $expectedNav .= '<li class="nav-test-title-1"><a href="/testslug1">Test Title 1</a></li>' . "\n";
        $expectedNav .= '</ul>' . "\n";
        
        $this->assertEquals($expectedNav, simple_pages_navigation($aboutPage->id, null, 'order', true, false));
    }
    
    public function testSimplePagesNavigationForOnlyAddToPublicNavChildrenPages()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals(1, count($pages));
        $aboutPage = $pages[0];
                
        $testPage1 = $this->_addTestPage('Test Title 1', 'testslug1', 'testtext1');
        $testPage1->parent_id = $aboutPage->id;
        $testPage1->order = 1;
        $testPage1->add_to_public_nav = 0;
        $testPage1->is_published = 1;
        $testPage1->save();
        
        $testPage2 = $this->_addTestPage('Test Title 2', 'testslug2', 'testtext2');
        $testPage2->parent_id = $aboutPage->id;
        $testPage2->order = 2;
        $testPage2->add_to_public_nav = 1;
        $testPage2->is_published = 0;
        $testPage2->save();
        
        $testPage0 = $this->_addTestPage('Test Title 0', 'testslug0', 'testtext0');
        $testPage0->parent_id = $aboutPage->id;
        $testPage0->order = 0;
        $testPage0->is_published = 0;
        $testPage0->add_to_public_nav = 0;
        $testPage0->save();

        $this->dispatch('about');
        
        $expectedNav = '';
        $expectedNav .= '<ul class="simple-pages-navigation">' . "\n";
        $expectedNav .= '<li class="nav-test-title-2"><a href="/testslug2">Test Title 2</a></li>' . "\n";
        $expectedNav .= '</ul>' . "\n";
        
        $this->assertEquals($expectedNav, simple_pages_navigation($aboutPage->id, null, 'order', false, true));
    }
}
