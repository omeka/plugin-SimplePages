<?php
class SimplePagesPageTable extends Omeka_Db_Table
{
    public function findPages() {
        $select = $this->getSelect();
        $pages = $this->fetchObjects($select);
        return $pages;
    }
}