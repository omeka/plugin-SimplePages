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
      `add_to_public_nav` tinyint(1) NOT NULL,
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
      KEY `add_to_public_nav` (`add_to_public_nav`),
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
    $page->add_to_public_nav = 1;
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

function simple_pages_upgrade($oldVersion, $newVersion)
{
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
function simple_pages_define_acl($acl)
{
    // SimplePages administrative interface.
    // Omeka_Acl_Resource is deprecated in 2.0.
    if (version_compare(OMEKA_VERSION, '2.0-dev', '<')) {
        $indexResource = new Omeka_Acl_Resource('SimplePages_Index');
        $indexResource->add(array('add', 'delete', 'edit', 'browse'));
        $pageResource = new Omeka_Acl_Resource('SimplePages_Page');
        $pageResource->add(array('show-unpublished'));
    } else {
        $indexResource = new Zend_Acl_Resource('SimplePages_Index');
        $pageResource = new Zend_Acl_Resource('SimplePages_Page');
    }
    $acl->add($indexResource);
    $acl->add($pageResource);
    $acl->allow('super', 'SimplePages_Index');
    $acl->allow('admin', 'SimplePages_Index');    
    $acl->allow('super', 'SimplePages_Page');
    $acl->allow('admin', 'SimplePages_Page');
    $acl->allow(null, 'SimplePages_Page', 'show');
    $acl->deny(null, 'SimplePages_Page', 'show-unpublished');
}

/**
 * Add the routes for accessing simple pages by slug, and for
 * replacing the public-side home page.
 *
 * @param Zend_Controller_Router_Rewrite $router
 */
function simple_pages_define_routes($router)
{
    // No special routing for the admin side.
    if (is_admin_theme()) {
        return;
    }

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

        if (simple_pages_is_home_page($page)) {
            $router->addRoute(
                'simple_pages_show_home_page_' . $page->id, 
                new Zend_Controller_Router_Route(
                    '/', 
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
function simple_pages_page_caching_blacklist_for_record($blacklist, $record, $action)
{
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
        $nav[__('Simple Pages')] = uri('simple-pages');
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
    $navLinks = simple_pages_get_links_for_children_pages(0, 0, 'order', true, true);
    foreach($navLinks as $text => $uri) {
        $nav[$text] = $uri;
    }
    return $nav;
}

function simple_pages_select_parent_page($page)
{   
    $valuePairs = array('0' => __('Main Page (No Parent)'));
    $potentialParentPages = get_db()->getTable('SimplePagesPage')->findPotentialParentPages($page->id);    
    foreach($potentialParentPages as $potentialParentPage) {
        if (trim($potentialParentPage->title) != '') {
            $valuePairs[$potentialParentPage->id] = $potentialParentPage->title;
        }
    }
    
    return __v()->formSelect('parent_id', 
                       $page->parent_id,
                       array('id'=>'simple-pages-parent-id'),
                       $valuePairs) . "\n";
}

function simple_pages_display_hierarchy($parentPageId = 0, $partialFilePath='index/browse-hierarchy-page.php')
{
    $html = '';
    $childrenPages = get_db()->getTable('SimplePagesPage')->findChildrenPages($parentPageId);
    if (count($childrenPages)) {        
        $html .= '<ul>';
        foreach($childrenPages as $childPage) {
            $html .= '<li>';
            $html .= __v()->partial($partialFilePath, array('simplePage'=>$childPage));
            $html .= simple_pages_display_hierarchy($childPage->id, $partialFilePath);
            $html .= '</li>';
        }
        $html .= '</ul>';
    }
    return $html;
}
