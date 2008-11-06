<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SimplePages
 */

/**
 * The Simple Pages index controller class.
 *
 * @package SimplePages
 * @author CHNM
 * @copyright Center for History and New Media, 2008
 */
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
        // Get all the pages in the database, ordered by slug.
        $pages = $this->getTable('SimplePagesPage')->findAllPagesOrderBySlug();
        $this->view->pages = $pages;
    }
    
    public function addAction()
    {
        // Create a new page.
        $page = new SimplePagesPage;
        // Set the created by user ID.
        $page->created_by_user_id = current_user()->id;
        $this->_processPageForm($page, 'add');
    }
    
    public function editAction()
    {
        // Get the requested page.
        $page = $this->findById();
        $this->_processPageForm($page, 'edit');
    }
    
    /**
     * Process the page edit and edit forms.
     */
    private function _processPageForm($page, $action)
    {
        // Attempt to save the form if there is a valid POST. If the form 
        // is successfully saved, set the flash message, unset the POST, 
        // and redirect to the browse action.
        try {
            if ($page->saveForm($_POST)) {
                if ('add' == $action) {
                    $this->flashSuccess("The page \"$page->title\" has been added.");
                } else if ('edit' == $action) {
                    $this->flashSuccess("The page \"$page->title\" has been edited.");
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
        // Set the page object to the view.
        $this->view->page = $page;
    }
    
    public function deleteAction()
    {
        $page = $this->findById();
        $page->delete();
        $this->flashSuccess("The page \"$page->title\" has been deleted.");
        $this->redirect->goto('browse');
        return;
    }
}