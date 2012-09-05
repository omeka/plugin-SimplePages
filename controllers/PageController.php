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
class SimplePages_PageController extends Omeka_Controller_AbstractActionController
{
    public function showAction()
    {
        // Get the page object from the passed ID.
        $pageId = $this->_getParam('id');
        $page = $this->_helper->db->getTable('SimplePagesPage')->find($pageId);
        
        // Redirect to the public theme's route if accessing the page via the 
        // admin theme or if somehow using the default route.
        $currentRouteName = $this->getFrontController()->getRouter()->getCurrentRouteName();
        if (is_admin_theme() || 'default' == $currentRouteName) {
            $this->_helper->redirector->goToUrl(WEB_ROOT . '/' . $page->slug);
        }
        
        // Restrict access to the page when it is not published.
        if (!$page->is_published 
            && !$this->_helper->acl->isAllowed('show-unpublished')) {
            throw new Omeka_Controller_Exception_403;
        }
        
        // Set the page object to the view.
        $this->view->simplePage = $page;
    }
}
