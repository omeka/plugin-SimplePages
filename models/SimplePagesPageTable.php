<?php
class SimplePagesPageTable extends Omeka_Db_Table
{
    protected $_name = 'simple_pages_pages';
    
    public function getPages() {
        $select = $this->getSelect()->order('published_date DESC');
        $pages = $this->fetchObjects($select);
        return $pages;
    }
}