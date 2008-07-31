<?php
/**
 * SimplePagesPageTable
 * @package: SimplePages
 */

class SimplePagesPageTable extends Omeka_Table {

	public function findRecent( $includeHidden=true, $maxPages = 0) {
				
		// get list of pages
		if ($includeHidden) {
			$sqlWhereClause = '1 ORDER BY published_date DESC';			
		} else {
			$sqlWhereClause = 'is_active = TRUE ORDER BY published_date DESC';
		}
		if (!empty($maxPages)) {
			$sqlWhereClause .= ' LIMIT ' . $maxPages;
		}
		$pages = $this->findBySql($sqlWhereClause, array());
		return $pages;
	}
	
}