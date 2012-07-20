<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SimplePages
 */

defined('SIMPLE_PAGES_DIR') or define('SIMPLE_PAGES_DIR', dirname(__FILE__));

// Add plugin hooks.
add_plugin_hook('install', 'simple_pages_install');
add_plugin_hook('uninstall', 'simple_pages_uninstall');
add_plugin_hook('upgrade', 'simple_pages_upgrade');
add_plugin_hook('define_acl', 'simple_pages_define_acl');
add_plugin_hook('define_routes', 'simple_pages_define_routes');
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

require_once SIMPLE_PAGES_DIR . '/helpers/SimplePageFunctions.php';
require_once SIMPLE_PAGES_DIR . '/functions.php';
