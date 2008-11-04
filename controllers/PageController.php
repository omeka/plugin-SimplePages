<?php
class SimplePages_PageController extends Omeka_Controller_Action
{    
    public function showAction() 
    {
        $pageId = $this->_getParam('id');        
        $page = $this->getTable('SimplePagesPage')->find($pageId);
        // Restrict access to the page when it is not published.
        if (!$page->is_published() && !$this->isAllowed('show-unpublished')) {
            $this->redirect->gotoUrl('403');
            return;
        }
        $this->view->page = $page;            
    }
}
