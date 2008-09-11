<?php
class SimplePagesPageTable extends Omeka_Db_Table
{
    protected $_name = 'simple_pages_pages';
    
    public function findRecent( $includeHidden=true, $maxPages = 0) {
        // Get list of pages.
        $select = '';
        
        if ($includeHidden) {
            $select = $this->getSelect()->where('1')->order('published_date DESC');            
        } else {
            $select = $this->getSelect()->where('is_active = TRUE')->order('published_date DESC');            
        }
        
        if (!empty($select) && !empty($maxPages)) {
            $select = $select->limit($maxPages);
        }
        
        $params = array();
        $pages = $this->fetchObjects($select, $params);
        
        return $pages;
    }
}