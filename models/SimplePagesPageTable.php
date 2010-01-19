<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SimplePages
 */

/**
 * The Simple Pages page table class.
 *
 * @package SimplePages
 * @author CHNM
 * @copyright Center for History and New Media, 2008
 */
class SimplePagesPageTable extends Omeka_Db_Table
{
    /**
     * Find all pages, ordered by slug name.
     */
    public function findAllPagesOrderBySlug()
    {
        $select = $this->getSelect()->order('slug');
        return $this->fetchObjects($select);
    }
    
    public function findPotentialParentPages($pageId)
    {
        // create a page lookup table for all of the pages
        $idToPageLookup = $this->createIdToPageLookup();        
                
        // find all of the page's descendants
        $descendantPages = $this->findChildren($pageId, true, $idToPageLookup);        
        
        // filter out all of the descendant pages from the lookup table
        $allPages = array_values($idToPageLookup);
        foreach($descendantPages as $descendantPage) {
            unset($idToPageLookup[$descendantPage->id]);
        }
        
        // filter out the page itself from the lookup table
        unset($idToPageLookup[$pageId]);

        // return the values of the filtered page lookup table
        return array_values($idToPageLookup);        
    }
    
    /**
     * Retrieve child pages from list of pages matching page ID.
     *
     * Matches against the pages parameter against the page ID. Also matches all
     * children for the same to retrieve all children of a page.
     *
     * @param int $parentId The id of the original parent
     * @param array $pages The array of all pages
     * @return array
     */
    public function findChildren($parentId, $includeAllDescendants=false, $idToPageLookup = null, $parentToChildrenLookup = null)
    {
        if ((string)$parentId == '') {
            return array();
        }
                
        $descendantPages = array();
        
    	if ($includeAllDescendants) {
            // create the id to page lookup if required
            if (!$idToPageLookup) {
                $idToPageLookup = $this->createIdToPageLookup();
            }            
            
            // create the parent to children lookup if required
        	if (!$parentToChildrenLookup) {
                $parentToChildrenLookup = $this->createParentToChildrenLookup($idToPageLookup);
            }                        

            // get all of the descendant pages of the parent page
        	$childrenPages = $parentToChildrenLookup[$parentId];
        	$descendantPages = array_merge($descendantPages, $childrenPages);        	
    	    foreach ( $childrenPages as $childPage ) {
    			if ( $allGrandChildren = $this->findChildren($childPage->id, true, $idToPageLookup, $parentToChildrenLookup) ) {
    			    $descendantPages = array_merge($descendantPages, $allGrandChildren);
    			}
        	}
        } else {           
            // only include the immediate children
            $descendantPages = $this->findBy(array('parent_id'=>$parentId, 'sort'=>'order'));
        }
        
        return $descendantPages;
    }
    
    public function createIdToPageLookup() 
    {
        // get all of the pages
        // this should eventually be just the id/parent_id pairs for all pages
        $allPages = $this->findAll();
        
        // create the page lookup                
        $idToPageLookup = array();
        foreach($allPages as $page) {
            $idToPageLookup[$page->id] = $page;
        }
        
        return $idToPageLookup;
    }
    
    public function createParentToChildrenLookup($idToPageLookup)
    {    
        // create an associative array that maps parent ids to an array of any children's ids
        $parentToChildrenLookup = array();
        $allPages = array_values($idToPageLookup);
        
        // initialize the children array for all potential parents
        foreach($allPages as $page) {
            $parentToChildrenLookup[$page->id] = array();
        }
        
        // add each child to his parent's array
        foreach($allPages as $page) {
            $parentToChildrenLookup[$page->parent_id][] = $page;
        }
        
        return $parentToChildrenLookup;
    }
    
    public function applySearchFilters($select, $params)
    {    
         if (isset($params['parent_id'])) {             
             $select->where('s.parent_id = ?', array($params['parent_id']));
         }
         
         if (isset($params['sort'])) {
             switch($params['sort']) {
                 case 'alpha':
                    $select->order('s.title ASC');
                    $select->order('s.order ASC');
                    break;
                 case 'order':
                    $select->order('s.order ASC');
                    $select->order('s.title ASC');
                    break;
             }
         }         
    }
}