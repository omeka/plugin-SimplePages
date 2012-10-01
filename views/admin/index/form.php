<?php 

class SimplePagesAdminForm extends Omeka_Form_Admin
{
        
    public function init()
    {
        
        parent::init();
        
        $this->addElementToEditGroup('text', 
                                     'title', 
                                      array('id'=>'simple-pages-title',
                                         'size'  => 40,
                                         'value' => simple_page('title'),                                         
                                         'label' => 'Title',
                                         'description' => 'The title of the page (required).',
                                         'required' => true
                                        ));
        
        $this->addElementToEditGroup('text', 
                                     'slug',
                                      array('id'=>'simple-pages-slug',
                                             'size'  => 40,
                                             'value'=>simple_page('slug'),
                                              'label' => 'Slug',
                                              'description'=>'The URL slug for the page. Automatically created from the title if not entered. Allowed characters: alphanumeric, underscores, dashes, and forward slashes.'
                                       ));
        
        $this->addElementToEditGroup('checkbox', 'use_tiny_mce',                                         
                                       array('id' => 'simple-pages-use-tiny-mce',
                                       'checked'=>simple_page('use_tiny_mce'),
                                       'values'=> array(1, 0),
                                       'label' => 'Use HTML editor?',
                                       'description'=>'This will enable an HTML editor for the simple page text below. <strong>Warning</strong>: if enabled, PHP code will not be evaluated and may be hidden from view. Be sure to remove PHP code that you don\'t want exposed in the HTML source.'
                                      ));              
                                               
        $this->addElementToEditGroup('textarea', 'text', 
                                       array('id'    => 'simple-pages-text',
                                             'cols'  => 50, 
                                             'rows'  => 25,
                                             'value' => simple_page('text'),
                                             'label' => 'Text',
                                             'description' => 'The content for the page (optional). HTML markup is allowed. PHP code is allowed if you are not using the HTML editor.'
                                        ));
        
        $page = get_current_simple_page();
        $parentOptions = simple_pages_get_parent_options($page);

        $this->addElementToSaveGroup('select', 'parent_id',
                                     array('id' => 'simple-pages-parent-id',
                                           'multiOptions' => $parentOptions,
                                           'value' => $page->parent_id,
                                           'label' => 'Parent',
                                           'description' => 'The parent page.'
                                          ));

        $this->addElementToSaveGroup('text', 'order', 
                                     array('value' => simple_page('order'),
                                           'label' => 'Order',
                                           'description' => 'The order of the page relative to the other pages with the same parent.'
                
                ));
        
        
        $this->addElementToSaveGroup('checkbox', 'is_published', 
                                      array('id' => 'simple_pages_is_published',
                                            'values' => array(1, 0),
                                            'checked' => simple_page('is_published'),
                                            'label' => 'Publish this page?',
                                            'description' => 'Checking this box will make the page public and it will appear in Simple Page navigation.'  
                        ));
        
        
    }    
}

