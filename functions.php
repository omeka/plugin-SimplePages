<?php

/**
 * Install the plugin.
 */
function simple_pages_install()
{
    // Create the table.
    $db = get_db();
    $sql = "
    CREATE TABLE IF NOT EXISTS `$db->SimplePagesPage` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `modified_by_user_id` int(10) unsigned NOT NULL,
      `created_by_user_id` int(10) unsigned NOT NULL,
      `is_published` tinyint(1) NOT NULL,
      `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
      `slug` tinytext COLLATE utf8_unicode_ci NOT NULL,
      `text` text COLLATE utf8_unicode_ci,
      `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `inserted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
      `order` int(10) unsigned NOT NULL,
      `parent_id` int(10) unsigned NOT NULL,
      `template` tinytext COLLATE utf8_unicode_ci NOT NULL,
      `use_tiny_mce` tinyint(1) NOT NULL,
      PRIMARY KEY (`id`),
      KEY `is_published` (`is_published`),
      KEY `inserted` (`inserted`),
      KEY `updated` (`updated`),
      KEY `created_by_user_id` (`created_by_user_id`),
      KEY `modified_by_user_id` (`modified_by_user_id`),
      KEY `order` (`order`),
      KEY `parent_id` (`parent_id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    $db->query($sql);
    
    // Save an example page.
    $page = new SimplePagesPage;
    $page->modified_by_user_id = current_user()->id;
    $page->created_by_user_id = current_user()->id;
    $page->is_published = 1;
    $page->parent_id = 0;
    $page->title = 'About';
    $page->slug = 'about';
    $page->text = '<p>This is an example page. Feel free to replace this content, or delete the page and start from scratch.</p>';
    $page->save();
}

/**
 * Uninstall the plugin.
 */
function simple_pages_uninstall()
{        
    // Drop the table.
    $db = get_db();
    $sql = "DROP TABLE IF EXISTS `$db->SimplePagesPage`";
    $db->query($sql);    
}

function simple_pages_upgrade($args)
{
    $oldVersion = $args['old_version'];
    $newVersion = $args['new_version'];
    $db = get_db();
    
    switch($oldVersion) {
        case '0.2':
        case '0.2.1':
        case '0.2.2':
        case '0.2.3':
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD INDEX ( `is_published` )";
            $db->query($sql);    
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD INDEX ( `inserted` ) ";
            $db->query($sql);    
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD INDEX ( `updated` ) ";
            $db->query($sql);    
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD INDEX ( `add_to_public_nav` ) ";
            $db->query($sql);    
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD INDEX ( `created_by_user_id` ) ";
            $db->query($sql);    
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD INDEX ( `modified_by_user_id` ) ";
            $db->query($sql);    
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD `order` INT UNSIGNED NOT NULL ";
            $db->query($sql);
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD INDEX ( `order` ) ";
            $db->query($sql);
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD `parent_id` INT UNSIGNED NOT NULL ";
            $db->query($sql);
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD INDEX ( `parent_id` ) ";
            $db->query($sql);
            
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD `template` TINYTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
            $db->query($sql);
        case '1.0':
        case '1.1':
        case '1.2':
        case '1.2.1':
            $sql = "ALTER TABLE `$db->SimplePagesPage` ADD `use_tiny_mce` TINYINT(1) NOT NULL";
            $db->query($sql);
        case '1.3':
        case '1.3.1':
            $db->query("ALTER TABLE `$db->SimplePagesPage` DROP `add_to_public_nav`");
            delete_option('simple_pages_home_page_id');
        break;
    }
}

function simple_pages_initialize()
{
    add_translation_source(dirname(__FILE__) . '/languages');
}

/**
 * Define the ACL.
 * 
 * @param Omeka_Acl
 */
function simple_pages_define_acl($args)
{
    $acl = $args['acl'];
    
    $indexResource = new Zend_Acl_Resource('SimplePages_Index');
    $pageResource = new Zend_Acl_Resource('SimplePages_Page');
    $acl->add($indexResource);
    $acl->add($pageResource);

    $acl->allow(array('super', 'admin'), array('SimplePages_Index', 'SimplePages_Page'));
    $acl->allow(null, 'SimplePages_Page', 'show');
    $acl->deny(null, 'SimplePages_Page', 'show-unpublished');
}

/**
 * Add the routes for accessing simple pages by slug.
 * 
 * @param Zend_Controller_Router_Rewrite $router
 */
function simple_pages_define_routes($args)
{
    // Don't add these routes on the admin side to avoid conflicts.
    if (is_admin_theme()) {
        return;
    }

    $router = $args['router'];

    // Add custom routes based on the page slug.
    $pages = get_db()->getTable('SimplePagesPage')->findAll();
    foreach ($pages as $page) {
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
 * Specify the default list of urls to whitelist
 * 
 * @param $whitelist array An associative array urls to whitelist, 
 * where the key is a regular expression of relative urls to whitelist 
 * and the value is an array of Zend_Cache front end settings
 * @return array The white list
 */
function simple_pages_page_caching_whitelist($whitelist)
{
    // Add custom routes based on the page slug.
    $pages = get_db()->getTable('SimplePagesPage')->findAll();
    foreach($pages as $page) {
        $whitelist['/' . trim($page->slug, '/')] = array('cache'=>true);
    }
        
    return $whitelist;
}

/**
 * Add pages to the blacklist
 * 
 * @param $blacklist array An associative array urls to blacklist, 
 * where the key is a regular expression of relative urls to blacklist 
 * and the value is an array of Zend_Cache front end settings
 * @param $record
 * @param $action
 * @return array The white list
 */
function simple_pages_page_caching_blacklist_for_record($blacklist, $args)
{
    $record = $args['record'];
    $action = $args['action'];

    if ($record instanceof SimplePagesPage) {
        $page = $record;
        if ($action == 'update' || $action == 'delete') {
            $blacklist['/' . trim($page->slug, '/')] = array('cache'=>false);
        }
    }
        
    return $blacklist;
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
function simple_pages_filter_html($args)
{
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $purifier = $args['purifier'];

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
    $nav[] = array(
        'label' => __('Simple Pages'),
        'uri' => url('simple-pages'),
        'resource' => 'SimplePages_Index',
        'privilege' => 'browse'
    );
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
    $navLinks = simple_pages_get_links_for_children_pages(0, 0, 'order', true);
    $nav = array_merge($nav, $navLinks);
    return $nav;
}

function simple_pages_search_record_types($recordTypes)
{
    $recordTypes['SimplePagesPage'] = __('Simple Page');
    return $recordTypes;
}
