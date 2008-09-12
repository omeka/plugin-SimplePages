<?php 
/**
* SimplePages Plugin
* 
* @author CHNM
* @copyright CHNM, 2 February 2008
* @package SimplePages
*
**/
define('SIMPLE_PAGES_PLUGIN_VERSION', 0.1);
define('SIMPLE_PAGES_PAGE_PATH', 'simple-pages/');

add_plugin_directories();

require_once 'SimplePagesPage.php';

add_plugin_hook('add_routes', 'simple_pages_routes');
add_plugin_hook('install', 'simple_pages_install');
add_plugin_hook('theme_header', 'simple_pages_css');

add_filter('admin_navigation_main', 'simple_pages_main_nav');

function simple_pages_main_nav($navArray) {
    return $navArray + array('SimplePages' => url_for('simple-pages'));
}

function simple_pages_install() {
    
    // set default configuration variables for plugin    
    set_option('simple_pages_plugin_version', SIMPLE_PAGES_PLUGIN_VERSION);    
    set_option('simple_pages_page_path', simple_pages_clean_path(SIMPLE_PAGES_PAGE_PATH));
    
    $db = get_db();
    
    $sql = "
    CREATE TABLE IF NOT EXISTS `$db->SimplePagesPage` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` INT UNSIGNED NOT NULL,
        `title` TEXT NOT NULL,
        `html` TEXT NOT NULL,
        `css` TEXT NOT NULL,
        `slug` TEXT NOT NULL,
        `published_date` DATETIME NOT NULL,
        `is_published` BOOL NOT NULL,
        PRIMARY KEY (`id`),
        INDEX (`user_id`) 
    ) ENGINE = MYISAM ;";
    
    // create simple pages table in database if needed
    $db->exec($sql);
}

function simple_pages_routes($router) {
    
    $bp = 'simple-pages/';
    
    // add Pages plugin routes
    $router->addRoute('simple_pages_default', 
                      new Zend_Controller_Router_Route($bp, 
                                                       array('controller' => 'page', 
                                                             'action'     => 'browse', 
                                                             'module'     => 'simplepages')));
    $router->addRoute('simple_pages_links', 
                      new Zend_Controller_Router_Route($bp . ':action', 
                                                       array('controller' => 'page', 
                                                             'module'     => 'simplepages')));
    $router->addRoute('simple_pages_edit_page', 
                      new Zend_Controller_Router_Route($bp . 'edit-page/:id', 
                                                       array('controller' => 'page', 
                                                             'action'     => 'edit-page', 
                                                             'module'     => 'simplepages')));
    $router->addRoute('simple_pages_show_page', 
                      new Zend_Controller_Router_Route($bp . 'show-page/:id', 
                                                       array('controller' => 'page', 
                                                             'action'     => 'show-page', 
                                                             'module'     => 'simplepages')));
    
    // add custom routes for all pages
    $db = get_db();
    $pages = $db->getTable('SimplePagesPage')->findRecent();
    foreach($pages as $page) {
        $pageSlug = $page['slug'];
        if (!empty($pageSlug) && $pageSlug != '/') {
            $router->addRoute('simple_pages_show_page_' . $page['id'], 
                              new Zend_Controller_Router_Route($page['slug'] . ':id', 
                                                               array('controller' => 'page', 
                                                                     'action'     => 'show-page', 
                                                                     'id'         => $page['id'], 
                                                                     'module'     => 'simplepages')));    
        }
    }
}

function simple_pages_clean_path($path)
{
    return trim(trim($path), '/') . '/';
}

function simple_pages_slug_url($page) 
{
    return WEB_ROOT . '/' . $page['slug'];
}

// the css style for the configure settings
function simple_pages_settings_css() 
{
    $html = '';
    $html .= '<style type="text/css" media="screen">';
    $html .= '#simple_pages_settings label, #simple_pages_settings input, #simple_pages_settings textarea {';
    $html .= 'display:block;';
    $html .= 'float:none;';
    $html .= '}';
    $html .= '#simple_pages_settings input, #simple_pages_settings textarea {';
    $html .= 'margin-bottom:1em;';
    $html .= '}';
    $html .= '</style>';
    echo $html;
}

// the css style for every page
function simple_pages_css() {
    $html = '';
    $html .= '<style type="text/css" media="screen">';
    $html .= '#simple_pages_comment_form label, #simple_pages_comment_form input, #simple_pages_comment_form textarea {';
    $html .= 'display:block;';
    $html .= 'float:none;';
    $html .= '}';
    $html .= '#simple_pages_comment_form label { font-size: 1.5em; }';
    $html .= '#simple_pages_comment_form input, #simple_pages_comment_form textarea {';
    $html .= 'margin-bottom:1em;';
    $html .= '}';
    $html .= '</style>';
    echo $html;
}

function simple_pages_update_slug_javascript() {
    $js = <<<SP_JS
    <script type="text/javascript" language="javascript">
        String.prototype.trim = function() {
            return this.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); 
        }
        
        function updateSlug() {
            var title = $('simple_pages_page_title').value;            
            title = title.replace(/\W+/g,' ');            
            title = title.trim().toLowerCase();
            var slug_words = title.split(" ");
            var slug = slug_words.join("-");
            
            $('simple_pages_page_slug').value = slug + '/';
        }
    </script>
SP_JS;
    return $js;
}