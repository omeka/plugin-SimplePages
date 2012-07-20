<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class SimplePages_SelectParentPageTest extends SimplePages_Test_AppTestCase
{   
    protected $_isAdminTest = true;

    public function testSelectParentPageWithChildrenPages()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals(1, count($pages));
        $aboutPage = $pages[0];
        
        $testPage1 = $this->_addTestPage('Test Title 1', 'testslug1', 'testtext1');

        $testPage2 = $this->_addTestPage('Test Title 2', 'testslug2', 'testtext2');
        $testPage2->parent_id = $testPage1->id;
        $testPage2->save();
                
        $testPage3 = $this->_addTestPage('Test Title 3', 'testslug3', 'testtext3');

        $testPage4 = $this->_addTestPage('Test Title 4', 'testslug4', 'testtext4');
        $testPage4->parent_id = $aboutPage->id;
        $testPage4->save();
        
        $this->dispatch('simple-pages/index/browse');
        
        $selectHtml = '';
        $selectHtml .= '<select name="parent_id" id="simple-pages-parent-id">' . "\n";
        $selectHtml .= '    <option value="0" label="Main Page (No Parent)" selected="selected">Main Page (No Parent)</option>' . "\n";
        $selectHtml .= '    <option value="1" label="About">About</option>' . "\n";
        $selectHtml .= '    <option value="4" label="Test Title 3">Test Title 3</option>' . "\n";
        $selectHtml .= '    <option value="5" label="Test Title 4">Test Title 4</option>' . "\n";
        $selectHtml .= '</select>' . "\n";
        $this->assertEquals($selectHtml, simple_pages_select_parent_page($testPage1));
    }
    
    public function testSelectParentPageWithoutChildrenPages()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals(1, count($pages));
        $aboutPage = $pages[0];
        
        $this->dispatch('simple-pages/index/browse');
        
        $selectHtml = '';
        $selectHtml .= '<select name="parent_id" id="simple-pages-parent-id">' . "\n";
        $selectHtml .= '    <option value="0" label="Main Page (No Parent)" selected="selected">Main Page (No Parent)</option>' . "\n";
        $selectHtml .= '</select>' . "\n";
        
        $this->assertEquals($selectHtml, simple_pages_select_parent_page($aboutPage));
    }
}

