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
     

            //20201110 CKC page's cover image
            $form->addElementToEditGroup( 'file', 'ckc_page_cover',
            array(
            'id' => 'simple-pages-cover',
            'label' => __('Cover image'),
            'description' => __('Upload an image file to be displayed as a cover photo. '
                . 'Maximum filesize is 1 MiB.<br/>Allowed image formats: gif, jpeg, jpeg2000, png, webp.<br/>Current file:<br/>%s',
                ( ( (string)$page['ckc_cover_image'] !== '' ) ? '<a href="'
                . CKC_SPAGES_COVERS_URI . '/' . $page['ckc_cover_image'] . '">'
                . CKC_SPAGES_COVERS_DIR . '/' . $page['ckc_cover_image'] . '</a>' : '-' ) )
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
                //BEGIN 20201109 CKC
                if ( empty( $_FILES['ckc_page_cover'] ) === false && (int)$_FILES['ckc_page_cover']['size'] > 0 ) {
                    if ( PHP_VERSION_ID > 70200 ) { //FILEINFO_EXTENSION is available in php 7.2.0+
                        $finfo = finfo_open(FILEINFO_EXTENSION);
                        $fext = explode( '/', finfo_file( $finfo, $_FILES['ckc_page_cover']['tmp_name'] ) )[0];

                    }
                    else { //for older php versions
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $fmime = finfo_file( $finfo, $_FILES['ckc_page_cover']['tmp_name'] );
                        switch ( $fmime ) {
                            case 'image/gif': $fext = 'gif'; break;
                            case 'image/jp2':
                            case 'image/jpeg': $fext = 'jpeg'; break;
                            case 'image/png': $fext = 'png'; break;
                            case 'image/web': $fext = 'webp'; break;
                        }
                    }
                    finfo_close( $finfo );

                    if ( isset( $fext ) === false || $fext === '???' ) { //20201123 fallback / crude header check
                        $f = fopen( $_FILES['ckc_page_cover']['tmp_name'], 'r' );
                        $fhead = strtoupper( bin2hex( fread( $f, 12 ) ) );
                        fclose( $f );
                        //https://www.filesignatures.net/index.php
                        //https://en.wikipedia.org/wiki/List_of_file_signatures
                        //GIF
                        //4749 4638 3761 -> GIF87a
                        //4749 4638 3961 -> GIF89a
                        //JPEG
                        //FFD8 FFDB
                        //FFD8 FFE0
                        //FFD8 FFE1
                        //FFD8 FFE2
                        //FFD8 FFEE
                        //PNG
                        //8950 4E47 0D0A 1A0A -> PNG
                        //WEBP
                        //5249 4646 file size 5745 4250 -> RIFF WEBP
                        if ( $fhead !== '' ) {
                            if ( in_array( substr( $fhead, 0, 8 ), array('FFD8FFDB', 'FFD8FFE0', 'FFD8FFE1', 'FFD8FFE2', 'FFD8FFEE'), true ) === true ) {
                                $fext = 'jpeg';
                            }
                            else if ( in_array( substr( $fhead, 0, 12 ), array('474946383761', '474946383961'), true ) === true ) {
                                $fext = 'gif';
                            }
                            else if ( substr( $fhead, 0, 8 ) === '52494646' && substr( $fhead, -8 ) === '57454250' ) {
                                $fext = 'webp';
                            }
                        }
                        unset( $fhead );
                    }

                    if ( isset( $fext ) === false || in_array( $fext, array('gif', 'jpeg', 'png', 'webp'), true ) === false ) {
                        throw new Omeka_File_MimeType_Exception(__('Could not detect any of the supported image formats (gif, jpeg, png or webp) in the given file.'));
                    }

                    $ckc_error = ''; //non–critical errors
                    $fname = uniqid() . '.' . $fext;
                    if ( file_exists( CKC_SPAGES_COVERS_DIR . '/' . $fname ) === true ) {
                        $ckc_error = __( 'Could not write cover image under %s filename.', $fname );
                    }
                    else if ( $_FILES['ckc_page_cover']['size'] > 1024 * 1024 * 1 ) {
                        $ckc_error = __('File size is greater than 1 MiB. File was not saved.');
                    }
                    else if ( disk_free_space(CKC_SPAGES_COVERS_DIR) < $_FILES['ckc_page_cover']['size'] * 100 ) {
                        //It's not a good idea to fill up the whole disk – safe treshold placed here is filesize * 100
                        $ckc_error = 'Cover image was not saved since the file\'s directory is running out of free space.';
                    }
                    else {
                        if ( move_uploaded_file( $_FILES['ckc_page_cover']['tmp_name'], CKC_SPAGES_COVERS_DIR . '/' . $fname ) === false ) {
                            $ckc_error = __('Cover image was not saved. Try checking the server logs for details.');
                        }
                    }

                    if ( (string)$page['ckc_cover_image'] !== '' && strlen( $page['ckc_cover_image'] ) > 4 ) {
                        //20201111: we're replacing or removing an image… in any case, deleting previous file is in order
                        //it is assumed here that $page['ckc_cover_image'] contains a safe value,
                        //if not, then someone was messing with the database, hence the removal of obvious buggers, just in case.
                        unlink( CKC_SPAGES_COVERS_DIR . '/' . str_replace( array('/', '*'), '', $page['ckc_cover_image'] ) );
                        $page['ckc_cover_image'] = null;
                    }
                    if ( $ckc_error === '' ) {
                        $page['ckc_cover_image'] = $fname;
                    }
                }
                //END
                
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
