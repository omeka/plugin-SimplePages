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
class SimplePages_IndexController extends Omeka_Controller_AbstractActionController
{    
    public function init()
    {
        // Set the model class so this controller can perform some functions, 
        // such as $this->findById()
        $this->_helper->db->setDefaultModelName('SimplePagesPage');
    }
    
    public function indexAction()
    {        
        // Always go to browse.
        $this->_helper->redirector('browse');
        return;
    }
    
    public function addAction()
    {
                
        // Create a new page.
        $page = new SimplePagesPage;
        
        // Set the created by user ID.
        $page->created_by_user_id = current_user()->id;
        $page->template = '';
        $page->order = 0;        
        $this->view->form = $this->_getForm($page);        
        $this->_processPageForm($page, 'add');
    }
    
    public function editAction()
    {
        // Get the requested page.
        $page = $this->_helper->db->findById();
        $this->view->form = $this->_getForm($page);
        $this->_processPageForm($page, 'edit');
    }
    
    protected function _getForm($page = null)
    { 
        $form = new Omeka_Form_Admin();
        $form->addElementToEditGroup('text',
                        'title',
                        array('id'=>'simple-pages-title',
                                'size'  => 40,
                                'value' => metadata($page, 'title'),
                                'label' => 'Title',
                                'description' => 'The title of the page (required).',
                                'required' => true
                        ));
        
        $form->addElementToEditGroup('text',
                        'slug',
                        array('id'=>'simple-pages-slug',
                                'size'  => 40,
                                'value'=> metadata($page, 'slug'),
                                'label' => 'Slug',
                                'description'=>'The URL slug for the page. Automatically created from the title if not entered. Allowed characters: alphanumeric, underscores, dashes, and forward slashes.'
                        ));
        
        $form->addElementToEditGroup('checkbox', 'use_tiny_mce',
                        array('id' => 'simple-pages-use-tiny-mce',
                                'checked'=> metadata($page, 'use_tiny_mce'),
                                'values'=> array(1, 0),
                                'label' => 'Use HTML editor?',
                                'description'=>'This will enable an HTML editor for the simple page text below. <strong>Warning</strong>: if enabled, PHP code will not be evaluated and may be hidden from view. Be sure to remove PHP code that you don\'t want exposed in the HTML source.'
                        ));
         
        $form->addElementToEditGroup('textarea', 'text',
                        array('id'    => 'simple-pages-text',
                                'cols'  => 50,
                                'rows'  => 25,
                                'value' => metadata($page, 'text'),
                                'label' => 'Text',
                                'description' => 'The content for the page (optional). HTML markup is allowed. PHP code is allowed if you are not using the HTML editor.'
                        ));
        
        $parentOptions = simple_pages_get_parent_options($page);
        
        $form->addElementToSaveGroup('select', 'parent_id',
                        array('id' => 'simple-pages-parent-id',
                                'multiOptions' => $parentOptions,
                                'value' => $page->parent_id,
                                'label' => 'Parent',
                                'description' => 'The parent page.'
                        ));
        
        $form->addElementToSaveGroup('text', 'order',
                        array('value' => metadata($page, 'order'),
                                'label' => 'Order',
                                'description' => 'The order of the page relative to the other pages with the same parent.'
        
                        ));
        
        
        $form->addElementToSaveGroup('checkbox', 'is_published',
                        array('id' => 'simple_pages_is_published',
                                'values' => array(1, 0),
                                'checked' => metadata($page, 'is_published'),
                                'label' => 'Publish this page?',
                                'description' => 'Checking this box will make the page public and it will appear in Simple Page navigation.'
                        ));
        
        return $form;
        
    }
    
    /**
     * Process the page edit and edit forms.
     */
    private function _processPageForm($page, $action)
    {
        if ($this->getRequest()->isPost()) {
            // Attempt to save the form if there is a valid POST. If the form 
            // is successfully saved, set the flash message, unset the POST, 
            // and redirect to the browse action.
            try {
                
                // store and unset the is_home_page post variable
                if (isset($_POST['is_home_page'])) {
                    $isHomePage = $_POST['is_home_page'];
                    unset($_POST['is_home_page']);
                }
                $page->setPostData($_POST);
                if ($page->save()) {
                    if ('add' == $action) {
                        $this->_helper->flashMessenger(__('The page "%s" has been added.', $page->title), 'success');
                    } else if ('edit' == $action) {
                        $this->_helper->flashMessenger(__('The page "%s" has been edited.', $page->title), 'success');
                    }
                    
                    // store the simple_pages_home_page_id option
                    if ($isHomePage == '1') {                    
                        set_option('simple_pages_home_page_id', $page->id);
                    } else if (get_option('simple_pages_home_page_id') == $page->id) {
                        set_option('simple_pages_home_page_id', '');
                    }
                    unset($_POST);
                    $this->_helper->redirector('browse');
                    return;
                }
            // Catch validation errors.
            } catch (Omeka_Validator_Exception $e) {
                $this->_helper->flashMessenger($e);
            }
        }

        // Set the page object to the view.
        $this->view->simple_pages_page = $page;
    }

    protected function _getDeleteSuccessMessage($record)
    {
        return __('The page "%s" has been deleted.', $record->title);
    }
}
