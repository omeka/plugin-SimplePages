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
        $this->view->simplePages = $pages;
    }
    
    public function addAction()
    {
        // Create a new page.
        $page = new SimplePagesPage;
        // Set the created by user ID.
        $page->created_by_user_id = current_user()->id;
        $page->template = '';
        $page->order = 0;
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
            
            // store and unset the is_home_page post variable
            if (isset($_POST['is_home_page'])) {
                $isHomePage = $_POST['is_home_page'];
                unset($_POST['is_home_page']);
            }

            if ($page->saveForm($_POST)) {
                if ('add' == $action) {
                    $this->flashSuccess(__('The page "%s" has been added.', $page->title));
                } else if ('edit' == $action) {
                    $this->flashSuccess(__('The page "%s" has been edited.', $page->title));
                }
                
                // store the simple_pages_home_page_id option
                if ($isHomePage == '1') {                    
                    set_option('simple_pages_home_page_id', $page->id);
                } else if (get_option('simple_pages_home_page_id') == $page->id) {
                    set_option('simple_pages_home_page_id', '');
                }
                unset($_POST);
                $this->redirect->goto('browse');
                return;
            }
        // Catch validation errors.
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        }

        // Set the page object to the view.
        $this->view->simplePage = $page;
    }

    protected function _getDeleteSuccessMessage($record)
    {
        return __('The page "%s" has been deleted.', $record->title);
    }
}
