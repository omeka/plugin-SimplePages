<?php
/**
 * Simple Pages
 *
 * @copyright Copyright 2008-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Simple Pages index controller class.
 *
 * @package SimplePages
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
        $formOptions = array('type' => 'simple_pages_page', 'hasPublicPage'=>true);
        if($page && $page->exists()) {
            $formOptions['record'] = $page;
        }
        
        $form = new Omeka_Form_Admin($formOptions);
        $form->addElementToEditGroup('text',
                        'title',
                        array('id'=>'simple-pages-title',
                                'size'  => 40,
                                'value' => metadata($page, 'title'),
                                'label' => 'Title',
                                'description' => 'Name and heading for the page (required)',
                                'required' => true
                        ));
        
        $form->addElementToEditGroup('text',
                        'slug',
                        array('id'=>'simple-pages-slug',
                                'size'  => 40,
                                'value'=> metadata($page, 'slug'),
                                'label' => 'Slug',
                                'description'=>'The slug is the part of the URL for this page. A slug will be created automatically from the title if one is not entered. Letters, numbers, underscores, dashes, and forward slashes are allowed.'
                        ));
        
        $form->addElementToEditGroup('checkbox', 'use_tiny_mce',
                        array('id' => 'simple-pages-use-tiny-mce',
                                'checked'=> metadata($page, 'use_tiny_mce'),
                                'values'=> array(1, 0),
                                'label' => 'Use HTML editor?',
                                'description'=>'Check this to add an HTML editor bar for easily creating HTML. <strong>PHP code will not be read in pages if this option is checked</strong>.'
                        ));
         
        $form->addElementToEditGroup('textarea', 'text',
                        array('id'    => 'simple-pages-text',
                                'cols'  => 50,
                                'rows'  => 25,
                                'value' => metadata($page, 'text'),
                                'label' => 'Text',
                                'description' => 'Add content for page, including HTML markup and PHP code (if the HTML editor is not checked above).'
                        ));
        
        $parentOptions = simple_pages_get_parent_options($page);
        
        $form->addElementToSaveGroup('select', 'parent_id',
                        array('id' => 'simple-pages-parent-id',
                                'multiOptions' => $parentOptions,
                                'value' => $page->parent_id,
                                'label' => 'Parent',
                                'description' => 'The parent page'
                        ));
        
        $form->addElementToSaveGroup('text', 'order',
                        array('value' => metadata($page, 'order'),
                                'label' => 'Order',
                                'description' => 'The order of the page relative to the other pages with the same parent'
        
                        ));
        
        
        $form->addElementToSaveGroup('checkbox', 'is_published',
                        array('id' => 'simple_pages_is_published',
                                'values' => array(1, 0),
                                'checked' => metadata($page, 'is_published'),
                                'label' => 'Publish this page?',
                                'description' => 'Checking this box will make the page public'
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
                $page->setPostData($_POST);
                if ($page->save()) {
                    if ('add' == $action) {
                        $this->_helper->flashMessenger(__('The page "%s" has been added.', $page->title), 'success');
                    } else if ('edit' == $action) {
                        $this->_helper->flashMessenger(__('The page "%s" has been edited.', $page->title), 'success');
                    }
                    
                    $this->_helper->redirector('browse');
                    return;
                }
            // Catch validation errors.
            } catch (Omeka_Validate_Exception $e) {
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
