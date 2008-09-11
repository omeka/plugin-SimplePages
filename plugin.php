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
add_plugin_hook('config_form', 'simple_pages_config_form');
add_plugin_hook('config', 'simple_pages_config');
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
    
    // get the base path
    $bp = get_option('simple_pages_page_path');
    //$bp = 'simplepages/';
    
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


function simple_pages_config($post) {
    
    set_option('simple_pages_page_path', 
               simple_pages_clean_path($post['simple_pages_page_path']));
    
    // If the page path is empty then make it the default page path.
    if (trim(get_option('simple_pages_page_path')) == '') {
        set_option('simple_pages_page_path', 
                   simple_pages_clean_path(SIMPLE_PAGES_PAGE_PATH));
    }
    $froute_pages = get_option('simple_pages_page_path');
}

function simple_pages_config_form() {
    simple_pages_settings_css();
    //this styling needs to be associated with appropriate hook
    $textInputSize = 30;
    $textAreaRows = 10;
    $textAreaCols = 50;
?>
<div id="simple_pages_settings">
    <label for="simple_pages_page_path">Relative Page Path From Project Root:</label>
    <p class="instructionText">Please enter the relative page path from the project root where you want the pages to be located. Use forward slashes to indicate subdirectories, but do not begin with a forward slash.</p>
    <input type="text" name="simple_pages_page_path" value="<?php echo settings('simple_pages_page_path'); ?>" size="<?php echo $textInputSize; ?>" />
</div>
<?php
}

function simple_pages_url($path) 
{
    return WEB_ROOT . '/' . settings('simple_pages_page_path') . $path;
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