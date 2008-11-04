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
        $this->view->pages = $pages;
    }
    
    public function addAction()
    {
        $page = new SimplePagesPage;
        $page->created_by_user_id = current_user()->id;
        return $this->_processPageForm($page);
    }
    
    public function editAction()
    {    
        $page = $this->findById();
        return $this->_processPageForm($page);
    }
    
    private function _processPageForm($page)
    {
        try {
            // Attempt to save the form.
            $returnValue = $page->saveForm($_POST);
            // If the form is saved, set the flash message and redirect to the 
            // browse action.
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
        // Catch validation errors.
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        // Catch any other errors that may occur.
        } catch (Exception $e) {
            $this->flash($e->getMessage());
        }
        $this->view->page = $page;
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