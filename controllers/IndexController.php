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
 * @package SimpleExhibits
 */
class SimpleExhibits_IndexController extends Omeka_Controller_AbstractActionController
{    
    public function init()
    {
        // Set the model class so this controller can perform some functions, 
        // such as $this->findById()
        $this->_helper->db->setDefaultModelName('SimpleExhibitsPage');
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
        $page = new SimpleExhibitsPage;
        
        // Set the created by user ID.
        $page->created_by_user_id = current_user()->id;
        $page->template = '';
        $page->order = 0;
        $form = $this->_getForm($page);
        $this->view->form = $form;
        $this->_processPageForm($page, $form, 'add');
    }
    
    public function editAction()
    {
        // Get the requested page.
        $page = $this->_helper->db->findById();
        $form = $this->_getForm($page);
        $this->view->form = $form;
        $this->_processPageForm($page, $form, 'edit');
    }
    
    protected function _getForm($page = null)
    { 
        $formOptions = array('type' => 'simple_exhibits_page', 'hasPublicPage' => true);
        if ($page && $page->exists()) {
            $formOptions['record'] = $page;
        }
        
        $form = new Omeka_Form_Admin($formOptions);
        $form->addElementToEditGroup(
            'text', 'title',
            array(
                'id' => 'simple-exhibits-title',
                'value' => $page->title,
                'label' => __('Title'),
                'description' => __('Name and heading for the page (required)'),
                'required' => true
            )
        );
        
        $form->addElementToEditGroup(
            'text', 'slug',
            array(
                'id' => 'simple-exhibits-slug',
                'value' => $page->slug,
                'label' => __('Slug'),
                'description' => __(
                    'The slug is the part of the URL for this page. A slug '
                    . 'will be created automatically from the title if one is '
                    . 'not entered. Letters, numbers, underscores, dashes, and '
                    . 'forward slashes are allowed.'
                )
            )
        );
     

        
        
        $form->addElementToEditGroup(
            'checkbox', 'use_tiny_mce_text',
            array(
                'id' => 'simple-exhibits-text-use-tiny-mce',
                'checked' => $page->use_tiny_mce,
                'values' => array(1, 0),
                'label' => __('Use HTML editor?'),
                'description' => __(
                    'Check this to add an HTML editor bar for easily creating HTML.'
                )
            )
        );



        $form->addElementToEditGroup(
            'textarea', 'text',
            array('id' => 'simple-exhibits-text',
                'cols'  => 50,
                'rows'  => 25,
                'value' => $page->text,
                'label' => __('Header text'), //'Text' to 'Header text'
                'description' => __(
                    'Add text to the header of the exhbibit page.'
                )
            )
        );
        
        //Allow usage of tinyMCE for content block.
        //ERROR 15.09.2022 - when checked adds HTML editor to text, not content
        //FIX 10.11.2022 - simple-exhibits-wysiwyg.js
    
        $form->addElementToEditGroup(
            'checkbox', 'use_tiny_mce_content',
            array(
                'id' => 'simple-exhibits-content-use-tiny-mce',
                'checked' => $page->use_tiny_mce,
                'values' => array(1, 0),
                'label' => __('Use HTML editor?'),
                'description' => __(
                    'Check this to add an HTML editor bar for easily creating HTML.'
                )
            )
        );

        $form->addElementToEditGroup(
            'textarea', 'content',
            array('id' => 'simple-exhibits-content',
                'cols'  => 50,
                'rows'  => 25,
                'value' => $page->content,
                'label' => __('Exhibit content'),
                'description' => __(
                    'Add content for the exhibit.' //Add info about supported formats / html tags?
                )
            )
        );
        /*
        $form->addElementToSaveGroup(
            'select', 'parent_id',
            array(
                'id' => 'simple-exhibits-parent-id',
                'multiOptions' => simple_exhibits_get_parent_options($page),
                'value' => $page->parent_id,
                'label' => __('Parent'),
                'description' => __('The parent page')
            )
        );
        
        $form->addElementToSaveGroup(
            'text', 'order',
            array(
                'value' => $page->order,
                'label' => __('Order'),
                'description' => __(
                    'The order of the page relative to the other pages with '
                    . 'the same parent'
                )
            )
        );
        */
        $form->addElementToSaveGroup(
            'checkbox', 'is_published',
            array(
                'id' => 'simple_exhibits_is_published',
                'values' => array(1, 0),
                'checked' => $page->is_published,
                'label' => __('Publish this page?'),
                'description' => __('Checking this box will make the page public')
            )
        );

        $form->addElementToSaveGroup(
            'checkbox', 'is_featured',
            array(
                'id' => 'simple_exhibits_is_featured',
                'values' => array(1, 0),
                'checked' => $page->is_featured,
                'label' => __('Make this page featured?'),
                'description' => __('Checking this box will make the page featured')
            )
        );


        if (class_exists('Omeka_Form_Element_SessionCsrfToken')) {
            $form->addElement('sessionCsrfToken', 'csrf_token');
        }
        
        return $form;
    }
    
    /**
     * Process the page edit and edit forms.
     */
    private function _processPageForm($page, $form, $action)
    {
        // Set the page object to the view.
        $this->view->simple_exhibits_page = $page;

        if ($this->getRequest()->isPost()) {
            if (!$form->isValid($_POST)) {
                $this->_helper->_flashMessenger(__('There was an error on the form. Please try again.'), 'error');
                return;
            }
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
    }

    protected function _getDeleteSuccessMessage($record)
    {
        return __('The page "%s" has been deleted.', $record->title);
    }
}
