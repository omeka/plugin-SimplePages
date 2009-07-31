<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SimplePages
 */

// Define the plugin version.
define('SIMPLE_PAGES_PLUGIN_VERSION', get_plugin_ini('SimplePages', 'version'));

// Require the record model for the simple_pages_page table.
require_once 'SimplePagesPage.php';

// Add plugin hooks.
add_plugin_hook('install', 'simple_pages_install');
add_plugin_hook('uninstall', 'simple_pages_uninstall');
add_plugin_hook('define_routes', 'simple_pages_define_routes');
add_plugin_hook('define_acl', 'simple_pages_define_acl');
add_plugin_hook('config_form', 'simple_pages_config_form');
add_plugin_hook('config', 'simple_pages_config');
add_plugin_hook('search_result', 'simple_pages_search_result');

// Custom plugin hooks from other plugins.
add_plugin_hook('html_purifier_form_submission', 'simple_pages_filter_html');

// Add filters.
add_filter('admin_navigation_main', 'simple_pages_admin_navigation_main');
add_filter('public_navigation_main', 'simple_pages_public_navigation_main');
add_filter('search_models', 'simple_pages_search_models');


/**
 * Install the plugin.
 */
function simple_pages_install()
{
    // Set the version.
    set_option('simple_pages_plugin_version', SIMPLE_PAGES_PLUGIN_VERSION);
    
    // Create the table.
    $db = get_db();
    $sql = "
    CREATE TABLE IF NOT EXISTS `$db->SimplePagesPage` (
      `id` int(10) unsigned NOT NULL auto_increment,
      `modified_by_user_id` int(10) unsigned NOT NULL,
      `created_by_user_id` int(10) unsigned NOT NULL,
      `is_published` tinyint(1) NOT NULL,
      `add_to_public_nav` tinyint(1) NOT NULL,
      `title` tinytext collate utf8_unicode_ci NOT NULL,
      `slug` tinytext collate utf8_unicode_ci NOT NULL,
      `text` text collate utf8_unicode_ci,
      `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
      `inserted` timestamp NOT NULL default '0000-00-00 00:00:00',
      PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    $db->query($sql);
    
    // Save an example page.
    $page = new SimplePagesPage;
    $page->modified_by_user_id = current_user()->id;
    $page->created_by_user_id = current_user()->id;
    $page->is_published = 1;
    $page->add_to_public_nav = 1;
    $page->title = 'About';
    $page->slug = 'about';
    $page->text = '<p>This is an example page. Feel free to replace this 
    content, or delete the page and start from scratch.</p>';
    $page->save();
}

/**
 * Uninstall the plugin.
 */
function simple_pages_uninstall()
{
    // Delete the plugin version number.
    delete_option('simple_pages_plugin_version');
    
    // Drop the table.
    $db = get_db();
    $sql = "DROP TABLE IF EXISTS `$db->SimplePagesPage`";
    $db->query($sql);
}

/**
 * Define the routes.
 * 
 * @param Zend_Controller_Router_Rewrite
 */
function simple_pages_define_routes($router)
{
    // Add custom routes based on the page slug.
    $pages = get_db()->getTable('SimplePagesPage')->findAll();
    foreach($pages as $page) {
        $router->addRoute(
            'simple_pages_show_page_' . $page->id, 
            new Zend_Controller_Router_Route(
                $page->slug, 
                array(
                    'module'       => 'simple-pages', 
                    'controller'   => 'page', 
                    'action'       => 'show', 
                    'id'           => $page->id
                )
            )
        );
    }
}

/**
 * Define the ACL.
 * 
 * @param Omeka_Acl
 */
function simple_pages_define_acl($acl)
{
    // Add a namespaced ACL to restrict access to the Simple Pages admin 
    // interface.
    $resource = new Omeka_Acl_Resource('SimplePages_Index');
    $resource->add(array('add', 'delete', 'edit', 'browse'));
    $acl->add($resource);
    // Deny every role, then allow only super and admin.
    $acl->deny(null, 'SimplePages_Index');
    $acl->allow('super', 'SimplePages_Index');
    $acl->allow('admin', 'SimplePages_Index');
    
    // Add a namespaced ACL to restrict access to unpublished pages on the 
    // public website.
    $resource = new Omeka_Acl_Resource('SimplePages_Page');
    $resource->add(array('show-unpublished'));
    $acl->add($resource);
    // Deny every role, then allow only super and admin.
    $acl->deny(null, 'SimplePages_Page');
    $acl->allow('super', 'SimplePages_Page');
    $acl->allow('admin', 'SimplePages_Page');
}

/**
 * Display a record on the search results page
 * 
 * @param string $record The record of the search result to display
 * @return void
 */
function simple_pages_search_result($record)
{
    switch(get_class($record)) {
        case 'SimplePagesPage':
            echo '<a href="' . WEB_ROOT . '/' . $record->slug . '">' . $record->title .   '</a>';
        break;
    }
}

function simple_pages_search_models($modelsToSearch)
{
    $modelsToSearch['SimplePagesPage'] = array('resourceName'=>'SimplePages_Page', 'showPrivatePermission'=>'show-unpublished') ;
    return $modelsToSearch;
}

function simple_pages_config_form()
{
    include 'config_form.php';
}

function simple_pages_config()
{
    set_option('simple_pages_filter_page_content', (int)(boolean)$_POST['simple_pages_filter_page_content']);
}

/**
 * Filter the 'text' field of the simple-pages form, but only if the 
 * 'simple_pages_filter_page_content' setting has been enabled from within the
 * configuration form.
 * 
 * @param Zend_Controller_Request_Http $request
 * @param HTMLPurifier $purifier
 * @return void
 **/
function simple_pages_filter_html($request, $purifier)
{
    // If we aren't editing or adding a page in SimplePages, don't do anything.
    if ($request->getModuleName() != 'simple-pages' or !in_array($request->getActionName(), array('edit', 'add'))) {
        return;
    }
    
    // Do not filter HTML for the request unless this configuration directive is on.
    if (!get_option('simple_pages_filter_page_content')) {
        return;
    }
    
    $post = $request->getPost();
    $post['text'] = $purifier->purify($post['text']);    
    $request->setPost($post);
}

/**
 * Add the Simple Pages link to the admin main navigation.
 * 
 * @param array Navigation array.
 * @return array Filtered navigation array.
 */
function simple_pages_admin_navigation_main($nav)
{
    if (has_permission('SimplePages_Index', 'browse')) {
        $nav['Simple Pages'] = uri('simple-pages');
    }
    return $nav;
}

/**
 * Add the page title to the public main navigation.
 * 
 * @param array Navigation array.
 * @return array Filtered navigation array.
 */
function simple_pages_public_navigation_main($nav)
{
    $pages = get_db()->getTable('SimplePagesPage')->findAll();
    foreach ($pages as $page) {
        // Only add the link to the public navigation if the page is published.
        if ($page->is_published && $page->add_to_public_nav) {
            $nav[$page->title] = uri($page->slug);
        }
    }
    return $nav;
}
