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
    
    /**
    * Takes a set of parameters and constructs a search query for Lucene.
    *
    * @param Zend_Search_Lucene_Search_Query_Boolean $searchQuery This is the
    * query that we construct.
    * @param array $requestParams This is an associative array where the key 
    * is the name of the parameter and the value is the value of the parameter.
    * Available parameters:
    * - modified_by_user_id
    * - created_by_user_id
    * - public
    * - updated
    * - inserted
    *
    */
    
    public function addAdvancedSearchQueryForLucene($searchQuery, $requestParams) 
    {
        if ($search = Omeka_Search::getInstance()) {
        
            foreach($requestParams as $requestParamName => $requestParamValue) {
                switch($requestParamName) {

                    case 'public':
                        if (is_true($requestParamValue)) {
                            $subquery = $search->getLuceneRequiredTermQueryForFieldName(Omeka_Search::FIELD_NAME_IS_PUBLIC, Omeka_Search::FIELD_VALUE_TRUE, true);
                            $searchQuery->addSubquery($subquery, true);
                        }
                    break;

                    case 'created_by_user_id':
                    //     // Must be logged in to view items specific to certain users
                    //     if (!$controller->isAllowed('browse', 'Users')) {
                    //         throw new Exception( 'May not browse by specific users.' );
                    //     }
                        if (is_numeric($requestParamValue) && ((int)$requestParamValue > 0)) {
                            $subquery = $search->getLuceneRequiredTermQueryForFieldName(array('SimplePagesPage', 'created_by_user_id'), $requestParamValue, true);
                            $searchQuery->addSubquery($subquery, true);
                        }
                    break;

                    case 'modified_by_user_id':
                    //     // Must be logged in to view items specific to certain users
                    //     if (!$controller->isAllowed('browse', 'Users')) {
                    //         throw new Exception( 'May not browse by specific users.' );
                    //     }
                        if (is_numeric($requestParamValue) && ((int)$requestParamValue > 0)) {
                            $subquery = $search->getLuceneRequiredTermQueryForFieldName(array('SimplePagesPage', 'modified_by_user_id'), $requestParamValue, true);
                            $searchQuery->addSubquery($subquery, true);
                        }
                    break;

                }
            }   
        }
    }    
}