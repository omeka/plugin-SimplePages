<?php
class SimplePages_IndexController extends Omeka_Controller_Action
{    
    public function init()
    {
        // Set the model class so this controller can perform some functions, 
        // such as $this->findById()
        $this->_modelClass = 'SimplePagesPage';
    }
    
    public function indexAction() 
    {
        // Always go to browse.
        $this->redirect->goto('browse');
        return;
    }
    
    public function browseAction()
    {
        // Get all the pages in the database.
        $pages = $this->getTable('SimplePagesPage')->findAll();
        $this->view->assign(compact('pages'));
    }
    
    public function addAction()
    {
        $page = new SimplePagesPage;
        return $this->_processPageForm($page, 'Add');
    }
    
    public function editAction()
    {    
        $page = $this->findById();
        return $this->_processPageForm($page, 'Edit');
    }
    
    private function _processPageForm($page, $actionName)
    {
        try {
            $returnValue = $page->saveForm($_POST);
            if ($returnValue) {
                if (array_key_exists('simple-pages-add-submit', $_POST)) {
                    $this->flashSuccess('A page has been added.');
                } else if (array_key_exists('simple-pages-edit-submit', $_POST)) {
                    $this->flashSuccess('A page has been edited.');
                }
                unset($_POST);
                $this->redirect->goto('browse');
                return;
            }
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        } catch (Exception $e) {
            $this->flash($e->getMessage());
        }
        $this->view->assign(compact('page', 'actionName'));
    }
    
    public function deleteAction()
    {
        $page = $this->findById();
        $page->delete();
        $this->flashSuccess('A page has been deleted.');
        $this->redirect->goto('browse');
        return;
    }
}