<?php
define('SIMPLE_PAGES_PLUGIN_VERSION', '0.2');

// Require the record model for the simple_pages_page table.
require_once 'SimplePagesPage.php';

add_plugin_hook('install', 'simple_pages_install');
add_plugin_hook('uninstall', 'simple_pages_uninstall');
add_plugin_hook('define_routes', 'simple_pages_define_routes');
add_plugin_hook('define_acl', 'simple_pages_define_acl');

add_filter('admin_navigation_main', 'simple_pages_admin_navigation_main');

function simple_pages_install()
{
    set_option('simple_pages_plugin_version', SIMPLE_PAGES_PLUGIN_VERSION);
    
    $db = get_db();
    $sql = "
    CREATE TABLE IF NOT EXISTS `$db->SimplePagesPage` (
      `id` int(10) unsigned NOT NULL auto_increment,
      `modified_by_user_id` int(10) unsigned NOT NULL,
      `published` tinyint(1) NOT NULL,
      `title` tinytext collate utf8_unicode_ci NOT NULL,
      `slug` tinytext collate utf8_unicode_ci NOT NULL,
      `text` text collate utf8_unicode_ci,
      `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
      `inserted` timestamp NOT NULL default '0000-00-00 00:00:00',
      PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    $db->query($sql);
}

function simple_pages_uninstall()
{
    delete_option('simple_pages_plugin_version');
    
    $db = get_db();
    $sql = "DROP TABLE IF EXISTS `$db->SimplePagesPage`";
    $db->query($sql);
}

function simple_pages_define_routes($router)
{
    // Add custom routes.
    $pages = get_db()->getTable('SimplePagesPage')->findAll();
    foreach($pages as $page) {
        // Do not at a route if the page is not published.
        if (!$page->published) {
            continue;
        }
        $router->addRoute(
            'simple_pages_show_page_' . $page->id, 
            new Zend_Controller_Router_Route(
                $page->slug, 
                array(
                    'module'     => 'simple-pages', 
                    'controller' => 'page', 
                    'action'     => 'show', 
                    'id'         => $page->id
                )
            )
        );
    }
}

function simple_pages_define_acl($acl)
{
    // add a namespaced acl.
    $resource = new Omeka_Acl_Resource('SimplePages_Index');
    $resource->add(array('add', 'delete', 'edit', 'browse'));
    $acl->add($resource);
    $acl->deny('contributor', 'SimplePages_Index');
    $acl->allow('super', 'SimplePages_Index');
    $acl->allow('admin', 'SimplePages_Index');
}

function simple_pages_admin_navigation_main($nav)
{
	if(has_permission('SimplePages_Index','browse')) {
    	$nav['Simple Pages'] = uri('simple-pages');
	}
	return $nav;
}