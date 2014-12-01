<?php
/**
 * Simple Pages
 *
 * @copyright Copyright 2008-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Get the public navigation links for children pages of a parent page.
 *
 * @uses public_url()
 * @param integer|null The id of the parent page.  If null, it uses the current simple page
 * @param string The method by which you sort pages. Options are 'order' (default) and 'alpha'.
 * @param boolean Whether to return only published pages.
 * @return array The navigation links.
 */
function simple_pages_get_links_for_children_pages($parentId = null, $sort = 'order', $requiresIsPublished = false)
{
    if ($parentId === null) {
        $parentPage = get_current_record('simple_pages_page', false);
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

    $pages = get_db()->getTable('SimplePagesPage')->findBy($findBy);

    $navLinks = array();

    foreach ($pages as $page) {
        $uri = public_url($page->slug);

        $subNavLinks = simple_pages_get_links_for_children_pages($page->id, $sort, $requiresIsPublished);
        if (count($subNavLinks) > 0) {
            $navLinks[] = array(
                'label' => $page->title,
                'uri' => $uri,
                'pages' => $subNavLinks
            );
        } else {
            $navLinks[] = array(
                'label' => $page->title,
                'uri' => $uri,
            );
        }
    }
    return $navLinks;
}

/**
* Returns a nested unordered list of SimplePage links
*
* @uses simple_pages_get_links_for_children_pages()
* @uses nav()
* @param integer|null The id of the parent page.  If null, it uses the current simple page
* @param string The method by which you sort pages. Options are 'order' (default) and 'alpha'.
* @param boolean Whether to return only published pages.
* @return string
*/
function simple_pages_navigation($parentId = 0, $sort = 'order', $requiresIsPublished = true)
{
    $html = '';
    $childPageLinks = simple_pages_get_links_for_children_pages($parentId, $sort, $requiresIsPublished);
    if ($childPageLinks) {
        $html .= '<div class="simple-pages-navigation">' . "\n";
        $html .= nav($childPageLinks);
        $html .= '</div>' . "\n";
    }
    return $html;
}

/**
 * Returns a breadcrumb for a given page.
 *
 * @uses public_url(), html_escape()
 * @param integer|null The id of the page.  If null, it uses the current simple page.
 * @param string $separator The string used to separate each section of the breadcrumb.
 * @param boolean $includePage Whether to include the title of the current page.
 */
function simple_pages_display_breadcrumbs($pageId = null, $seperator=' > ', $includePage=true)
{
    $html = '';

    if ($pageId === null) {
        $page = get_current_record('simple_pages_page', false);
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
                $pageLinks[] = '<a href="' . public_url($bPage->slug) .  '">' . html_escape($bPage->title) . '</a>';
            }
        }
        $pageLinks[] = '<a href="'. public_url('') . '">' . __('Home') . '</a>';

        // create the bread crumb
        $html .= implode(html_escape($seperator), array_reverse($pageLinks));
    }
    return $html;
}

/**
 * Recursively list the pages under a page for editing.
 *
 * @param SimplePage $page A page to list.
 * @return string
 */
function simple_pages_edit_page_list($page)
{
    $html = '<li class="page" id="page_' . $page->id . '">';
    $html .= '<div class="sortable-item">';
    $html .= sprintf('<a href="%s">%s</a>', html_escape(record_url($page)), html_escape($page->title));
    $html .= ' (' . html_escape($page->slug) . ')';
    $html .= '<ul class="action-links group">';
    $html .= '<li>' . sprintf('<a href="%s" id="simplepage-%s" class="simplepage toggle-status status %s">%s</a>',
        ADMIN_BASE_URL,
       $page->id,
       ($page->is_published ? 'public' : 'private'),
       ($page->is_published ? __('Published') : __('Private'))) . '</li>';
    $html .= '<li>' . link_to($page, 'edit', __('Edit'), array('class' => 'edit')) . '</li>';
    $html .= '</ul>';
    $html .= '<br />';
    $html .= __('<strong>%1$s</strong> on %2$s',
        html_escape(metadata($page, 'modified_username')),
        html_escape(format_date(metadata($page, 'updated'), Zend_Date::DATETIME_SHORT)));
    $html .= '<a class="delete-toggle delete-element" href="#">' . __('Delete') . '</a>';
    $html .= '</div>';

    $childrenPages = $page->getChildren();
    if (count($childrenPages)) {
        $html .= '<ul>';
        foreach ($childrenPages as $childPage) {
            $html .= simple_pages_edit_page_list($childPage);
        }
        $html .= '</ul>';
    }
    $html .= '</li>';
    return $html;
}

/**
 * Returns the earliest ancestor page for a given page.
 *
 * @param integer|null The id of the page. If null, it uses the current simple page.
 * @return SimplePagesPage|null
 */
function simple_pages_earliest_ancestor_page($pageId)
{
    if ($pageId === null) {
        $page = get_current_record('simple_pages_page');
    } else {
        $page = get_db()->getTable('SimplePagesPage')->find($pageId);
    }

    $pageAncestors = get_db()->getTable('SimplePagesPage')->findAncestorPages($page->id);
    return end($pageAncestors);
}

function simple_pages_get_parent_options($page)
{
    $valuePairs = array('0' => __('Main Page (No Parent)'));
    $potentialParentPages = get_db()->getTable('SimplePagesPage')->findPotentialParentPages($page->id);
    foreach($potentialParentPages as $potentialParentPage) {
        if (trim($potentialParentPage->title) != '') {
            $valuePairs[$potentialParentPage->id] = $potentialParentPage->title;
        }
    }
    return $valuePairs;
}

/**
 * Update orders of all simple pages that have been modified.
 */
function simple_pages_update_order($newOrder)
{
    $db = get_db();
    $table = $db->SimplePagesPage;

    // Pages are ordered by order, then by title, so two passes are needed.

    // First step: update parent if needed.
    $sql = "SELECT `id`, `parent_id` FROM `$table` ORDER BY `order` ASC, `title` ASC";
    $currentOrder = $db->fetchPairs($sql);

    foreach ($newOrder as $id => $parentId) {
        if ($currentOrder[$id] != $parentId) {
            $db->update($table,
                array('parent_id' => $parentId),
                'id = ' . $id);
            // Update old hierarchy for next step.
            $currentOrder[$id] = $parentId;
        }
    }

    // Second step: update order if needed for each sibling.
    // For each parent, check if current children are ordered as new ones.
    while (!empty($newOrder)) {
        $parentId = reset($newOrder);

        // Get all current and new pages with this parent.
        $currentChildren = array_intersect($currentOrder, array($parentId));
        $newChildren = array_intersect($newOrder, array($parentId));

        // Compare them and update all values if they are different.
        // Orders are compared as csv because no function allows to check order.
        if (implode(',', array_keys($currentChildren)) != implode(',', array_keys($newChildren))) {
            // Order by 10 for easier insert and update of edited pages.
            $order = 10;
            foreach ($newChildren as $id => $parentId) {
                $db->update($table,
                    array('order' => $order),
                    'id = ' . $id);
                $order += 10;
            }
        }

        // Remove filtered keys before loop.
        $currentOrder = array_diff_key($currentOrder, $currentChildren);
        $newOrder = array_diff_key($newOrder, $newChildren);
    }
}
