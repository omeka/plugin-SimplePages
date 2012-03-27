<?php

/**
 * Returns the current simple page.
 *
 * @return SimplePagesPage|null
 **/
function simple_pages_get_current_page()
{
    return __v()->simplePage;
}

/**
 * Sets the current simple page.
 *
 * @param SimplePagesPage|null $simplePage
 * @return void
 **/
function simple_pages_set_current_page($simplePage=null)
{
    __v()->simplePage = $simplePage;
}

/**
 * Get the public navigation links for children pages of a parent page.
 *
 * @uses simple_pages_get_current_page()
 * @uses simple_pages_is_home_page()
 * @uses abs_uri()
 * @uses uri()
 * @uses simple_pages_get_links_for_children_pages()
 * @param integer|null The id of the parent page.  If null, it uses the current simple page
 * @param $currentDepth The number of levels down the subnavigation is.
 * @param string The method by which you sort pages. Options are 'order' (default) and 'alpha'.
 * @param boolean Whether to return only published pages.
 * @param boolean Whether to return pages explicitly added to the public navigation.
 * @return array The navigation links.
 */
function simple_pages_get_links_for_children_pages($parentId = null, $currentDepth = 0, $sort = 'order', $requiresIsPublished = false, $requiresIsAddToPublicNav = false)
{
    if ($parentId === null) {
        $parentPage = simple_pages_get_current_page();
        if ($parentPage) {
            $parentId = $parentPage->id;
        } else {
            $parentId = 0;
        }
    }

    $findBy = array('parent_id' => $parentId, 'sort' => $sort);
    if ($requiresIsPublished) {
        $findBy['is_published'] = $requiresIsPublished ? 1 : 0;
    }
    if ($requiresIsAddToPublicNav) {
        $findBy['add_to_public_nav'] = $requiresIsAddToPublicNav ? 1 : 0;
    }

    $pages = get_db()->getTable('SimplePagesPage')->findBy($findBy);

    $navLinks = array();

    foreach ($pages as $page) {
        // If the simple page is set to be the home page, use the home page url instead of the slug
        if (simple_pages_is_home_page($page)) {
           $uri = abs_uri('');
        } else {
            $uri = uri($page->slug);
        }

        $subNavLinks = simple_pages_get_links_for_children_pages($page->id, $currentDepth + 1, $sort, $requiresIsPublished, $requiresIsAddToPublicNav);
        if (count($subNavLinks) > 0) {
            $subNavClass = 'subnav-' . ($currentDepth + 1);
            $navLinks[$page->title] = array('uri' => $uri, 'subnav_attributes' => array('class' => $subNavClass), 'subnav_links' => $subNavLinks);
        } else {
            $navLinks[$page->title] = $uri;
        }
    }
    return $navLinks;
}

/**
 * Returns whether a simple page is the home page.
 *
 * @param SimplePagesPage The simple page
 * @return boolean
 */
function simple_pages_is_home_page($simplePage)
{
    if ($simplePage === null || $simplePage->id === null) {
        return false;
    }
    return (((string)$simplePage->id) == get_option('simple_pages_home_page_id'));
}

/**
* Gets the current simple page
*
* @return SimplePagesPage|null
**/
function get_current_simple_page()
{
    return simple_pages_get_current_page();
}

/**
 * Sets the current simple page
 *
 * @see loop_simple_pages()
 * @param SimplePagesPage
 * @return void
 **/
function set_current_simple_page(SimplePagesPage $simplePage)
{
   simple_pages_set_current_page($simplePage);
}

/**
 * Sets the simple pages for loop
 *
 * @param array $simplePages
 * @return void
 **/
function set_simple_pages_for_loop($simplePages)
{
    __v()->simplePages = $simplePages;
}

/**
 * Get the set of simple pages for the current loop.
 *
 * @return array
 **/
function get_simple_pages_for_loop()
{
    return __v()->simplePages;
}

/**
 * Loops through simple pages assigned to the view.
 *
 * @return mixed The current simple page
 */
function loop_simple_pages()
{
    return loop_records('simplePages', get_simple_pages_for_loop(), 'set_current_simple_page');
}

/**
 * Determine whether or not there are any simple pages in the database.
 *
 * @return boolean
 **/
function has_simple_pages()
{
    return (total_simple_pages() > 0);
}

/**
 * Determines whether there are any simple pages for loop.
 * @return boolean
 */
function has_simple_pages_for_loop()
{
    $view = __v();
    return ($view->simplePages and count($view->simplePages));
}

/**
  * Returns the total number of simple pages in the database
  *
  * @return integer
  **/
 function total_simple_pages()
 {
     return get_db()->getTable('SimplePagesPage')->count();
 }

/**
* Gets a property from an simple page
*
* @param string $propertyName
* @param array $options
* @param Exhibit $simplePage  The simple page
* @return mixed The simple page property value
**/
function simple_page($propertyName, $options=array(), $simplePage=null)
{
    if (!$simplePage) {
        $simplePage = get_current_simple_page();
    }

    if (property_exists(get_class($simplePage), $propertyName)) {
        return $simplePage->$propertyName;
    } else {
        return null;
    }
}

/**
* Returns a nested unordered list of SimplePage links
*
* @uses simple_pages_get_links_for_children_pages()
* @uses nav()
* @param integer|null The id of the parent page.  If null, it uses the current simple page
* @param $currentDepth The number of levels down the subnavigation is.
* @param string The method by which you sort pages. Options are 'order' (default) and 'alpha'.
* @param boolean Whether to return only published pages.
* @param boolean Whether to return pages explicitly added to the public navigation.
* @return string
**/
function simple_pages_navigation($parentId = 0, $currentDepth = null, $sort = 'order', $requiresIsPublished = true, $requiresIsAddToPublicNav = false)
{
    $html = '';
    $childPageLinks = simple_pages_get_links_for_children_pages($parentId, $currentDepth, $sort, $requiresIsPublished, $requiresIsAddToPublicNav);
    if ($childPageLinks) {
        $html .= '<ul class="simple-pages-navigation">' . "\n";
        $html .= nav($childPageLinks, $currentDepth);
        $html .= '</ul>' . "\n";
    }
    return $html;
}

/**
 * Returns a breadcrumb for a given page.
 *
 * @uses uri(), html_escape()
 * @param integer|null The id of the page.  If null, it uses the current simple page.
 * @param string $separator The string used to separate each section of the breadcrumb.
 * @param boolean $includePage Whether to include the title of the current page.
 **/
function simple_pages_display_breadcrumbs($pageId = null, $seperator=' > ', $includePage=true)
{
    $html = '';

    if ($pageId === null) {
        $page = get_current_simple_page();
    } else {
        $page = get_db()->getTable('SimplePagesPage')->find($pageId);
    }

    if ($page) {
        $ancestorPages = get_db()->getTable('SimplePagesPage')->findAncestorPages($page->id);
        $bPages = array_merge(array($page), $ancestorPages);

        // make sure all of the ancestors and the current page are published
        foreach($bPages as $bPage) {
            if (!$bPage->is_published) {
                $html = '';
                return $html;
            }
        }

        // find the page links
        $pageLinks = array();
        foreach($bPages as $bPage) {
            if ($bPage->id == $page->id) {
                if ($includePage) {
                    $pageLinks[] = html_escape($bPage->title);
                }
            } else {
                $pageLinks[] = '<a href="' . uri($bPage->slug) .  '">' . html_escape($bPage->title) . '</a>';
            }
        }
        $pageLinks[] = '<a href="'.uri('').'">' . __('Home') . '</a>';

        // create the bread crumb
        $html .= implode(html_escape($seperator), array_reverse($pageLinks));
    }
    return $html;
}

/**
 * Returns the earliest ancestor page for a given page.
 *
 * @uses get_current_simple_page()
 * @param integer|null The id of the page. If null, it uses the current simple page.
 * @return SimplePagesPage|null
 **/
function simple_pages_earliest_ancestor_page($pageId)
{
    if ($pageId === null) {
        $page = get_current_simple_page();
    } else {
        $page = get_db()->getTable('SimplePagesPage')->find($pageId);
    }

    $pageAncestors = get_db()->getTable('SimplePagesPage')->findAncestorPages($page->id);
    return end($pageAncestors);
}
