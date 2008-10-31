<?php
class SimplePages_PageController extends Omeka_Controller_Action
{    
    public function showAction() 
    {
        $pageId = $this->_getParam('id');        
        $page = $this->getTable('SimplePagesPage')->find($pageId);
        $this->view->page = $page;            
    }
}
