<?php
define('SIMPLE_PAGES_PLUGIN_VERSION', '0.2');

// Require the record model for the simple_pages_page table.
require_once 'SimplePagesPage.php';

add_plugin_hook('install', 'simple_pages_install');
add_plugin_hook('uninstall', 'simple_pages_uninstall');
add_plugin_hook('define_routes', 'simple_pages_define_routes');
add_plugin_hook('define_acl', 'simple_pages_define_acl');

add_filter('admin_navigation_main', 'simple_pages_admin_navigation_main');
add_filter('public_navigation_main', 'simple_pages_public_navigation_main');

function simple_pages_install()
{
    set_option('simple_pages_plugin_version', SIMPLE_PAGES_PLUGIN_VERSION);
    
    $db = get_db();
    $sql = "
    CREATE TABLE IF NOT EXISTS `simple_pages_pages` (
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

function simple_pages_admin_navigation_main($nav)
{
	if (has_permission('SimplePages_Index', 'browse')) {
    	$nav['Simple Pages'] = uri('simple-pages');
	}
	return $nav;
}

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