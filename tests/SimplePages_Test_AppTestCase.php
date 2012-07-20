<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/
class SimplePages_Test_AppTestCase extends Omeka_Test_AppTestCase
{
    const PLUGIN_NAME = 'SimplePages';
    
    public function setUp()
    {
        parent::setUp();
        
        // Authenticate and set the current user 
        $this->user = $this->db->getTable('User')->find(1);
        $this->_authenticateUser($this->user);
                
        require_once SIMPLE_PAGES_DIR . '/functions.php';
        // Add the plugin hooks and filters (including the install hook)
        $pluginBroker = get_plugin_broker();
        $this->_addPluginHooksAndFilters($pluginBroker, self::PLUGIN_NAME);
        
        $pluginHelper = new Omeka_Test_Helper_Plugin;
        $pluginHelper->setUp(self::PLUGIN_NAME);
        $this->_reloadRoutes();
    }
        
    public function _addPluginHooksAndFilters($pluginBroker, $pluginName)
    {   
        // Set the current plugin so the add_plugin_hook function works
        $pluginBroker->setCurrentPluginDirName($pluginName);
        
        // Add plugin hooks.
        add_plugin_hook('install', 'simple_pages_install');
        add_plugin_hook('uninstall', 'simple_pages_uninstall');
        add_plugin_hook('upgrade', 'simple_pages_upgrade');
        add_plugin_hook('define_acl', 'simple_pages_define_acl');
        add_plugin_hook('config_form', 'simple_pages_config_form');
        add_plugin_hook('config', 'simple_pages_config');
        add_plugin_hook('initialize', 'simple_pages_initialize');

        // Custom plugin hooks from other plugins.
        add_plugin_hook('html_purifier_form_submission', 'simple_pages_filter_html');

        // Add filters.
        add_filter('admin_navigation_main', 'simple_pages_admin_navigation_main');
        add_filter('public_navigation_main', 'simple_pages_public_navigation_main');

        add_filter('page_caching_whitelist', 'simple_pages_page_caching_whitelist');
        add_filter('page_caching_blacklist_for_record', 'simple_pages_page_caching_blacklist_for_record');        
    }
    
    public function assertPreConditions()
    {
        $pages = $this->db->getTable('SimplePagesPage')->findAll();
        $this->assertEquals(1, count($pages), 'There should be one page.');
        
        $aboutPage = $pages[0];
        $this->assertEquals('About', $aboutPage->title, 'The about page has the wrong title.');
        $this->assertEquals('about', $aboutPage->slug, 'The about page has the wrong slug.');
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
    
    protected function _addTestPage($title='Test Page', $slug='testpage', $text='whatever', $isPublished = true, $addToPublicNav = true) 
    {
        $page = new SimplePagesPage;
        $page->title = $title;
        $page->slug = $slug;
        $page->text = $text;
        $page->is_published = $isPublished ? '1' : '0';
        $page->add_to_public_nav = $addToPublicNav ? '1' : '0';
        $page->created_by_user_id = $this->user->id;
        $page->save();
        
        return $page;
    }
    
    protected function _addTestPages($minIndex=0, $maxIndex=0, $titlePrefix = 'Test Page ', $slugPrefix = 'testpage', $textPrefix = 'whatever')
    {
        $pages = array();
        for($i = $minIndex; $i <= $maxIndex; $i++) {
            $pages[] = $this->_addTestPage( $titlePrefix . $i, $slugPrefix . $i, $textPrefix . $i);
        }
        return $pages;
    }

    protected function _reloadRoutes()
    {
        simple_pages_define_routes(Zend_Controller_Front::getInstance()->getRouter());
    }
}
