<?php
class SimplePages_PageController extends Omeka_Controller_Action {    
    
    public function init() {}
    
    public function indexAction() 
    {
        $this->browseAction();
    }
        
    public function browseAction() 
    {
        // Do not act if no pages were submitted.
        if (isset($_POST['simple_pages_selected_pages'])) {
            
            $selectedPages = $this->getSelectedPages($_POST);
            $selectedAction = strtolower($_POST['simple_pages_selected_action']);
            
            switch($selectedAction) {
                case 'delete':
                    foreach($selectedPages as $page) {
                        $page->delete();
                    }
                    break;
                case 'hide':
                    foreach($selectedPages as $page) {
                        $page->is_published = 0;
                        $page->save();                        
                    }
                    break;
                case 'publish':
                    foreach($selectedPages as $page) {
                        $page->is_published = 1;
                        $page->save();
                    }
                    break;
                default:
                    break;
            }
        }
        
        $pages =  $this->getTable('SimplePagesPage')->getPages();                
        $this->view->pages = $pages;
    }
    
    protected function getSelectedPages($post)
    {
        $pages = array();
        foreach($post['simple_pages_selected_pages'] as $pageId) {
            $pages[] = $this->getTable('SimplePagesPage')->find($pageId);
        }
        return $pages;
    }
    
    public function addPageAction()
    {
        if ($_POST) {
            try {    
                $page = new SimplePagesPage();
                $page->user_id        = current_user()->id;
                $page->published_date = date('Y-m-d H:i:s');
                $page->title          = $_POST['simple_pages_page_title'];
                $page->html           = $_POST['simple_pages_page_html'];
                $page->css            = $_POST['simple_pages_page_css'];
                $page->slug           = simple_pages_clean_path($_POST['simple_pages_page_slug']);
                $page->is_published   = $_POST['simple_pages_page_is_published'] ? 1 : 0;
                $page->save();
            } catch (Exception $e) {
                throw $e;
            }
            $this->_redirect('simple-pages/browse/');
        }                
    }
    
    public function deletePageAction() 
    {
        $pageId = $this->_getParam('id');
        $page = $this->getTable('SimplePagesPage')->find($pageId);
        $page->delete();
        $this->_redirect('simple-pages/browse/');        
    }
    
    public function showPageAction() 
    {
        $pageId = $this->_getParam('id');        
        $page = $this->getTable('SimplePagesPage')->find($pageId);
        $this->view->page = $page;            
    }
    
    public function editPageAction() 
    {
        $pageId = $this->_getParam('id');
        $page = $this->getTable('SimplePagesPage')->find($pageId);
        
        if ($_POST) {
            if (!empty($_POST['delete'])) {
                $page->delete();
                $this->_redirect('simple-pages/browse/');
            } else {
                try {    
                    $page->user_id        = current_user()->id;
                    $page->published_date = date('Y-m-d H:i:s');
                    $page->title          = $_POST['simple_pages_page_title'];
                    $page->slug           = simple_pages_clean_path($_POST['simple_pages_page_slug']);
                    $page->html           = $_POST['simple_pages_page_html'];
                    $page->css            = $_POST['simple_pages_page_css'];
                    $page->is_published   = $_POST['simple_pages_page_is_published'] ? 1 : 0;
                    $page->save();
                } catch (Exception $e) {
                    throw $e;
                }
                $this->_redirect('simple-pages/browse/');
            }
        }
        $this->view->page = $page;
    }
}
