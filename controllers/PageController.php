<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SimplePages
 */

/**
 * The Simple Pages page controller class.
 *
 * @package SimplePages
 * @author CHNM
 * @copyright Center for History and New Media, 2008
 */
class SimplePages_PageController extends Omeka_Controller_Action
{
    public function showAction()
    {
        // Ge the page object from the passed ID.
        $pageId = $this->_getParam('id');
        $page = $this->getTable('SimplePagesPage')->find($pageId);
        
        // Restrict access to the page when it is not published.
        if (!$page->is_published 
            && !$this->isAllowed('show-unpublished')) {
            $this->redirect->gotoUrl('403');
            return;
        }
        
        // Set the page object to the view.
        $this->view->simplePage = $page;
    }
}
