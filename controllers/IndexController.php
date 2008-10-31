<?php
class SimplePages_IndexController extends Omeka_Controller_Action
{    
    public function indexAction() 
    {
    }
    
    public function browseAction()
    {
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
            $returnValue = $exhibit->saveForm($_POST);
            if ($returnValue) {
                // Will this work?
                if (array_key_exists('add_page',$_POST)) {
                    $this->flash('A page has been added.');
                } else if (array_key_exists('edit_page',$_POST)) {
                    $this->flash('A page has been edited.');
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
}
