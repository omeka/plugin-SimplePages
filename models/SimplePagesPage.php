<?php
require_once 'SimplePagesPageTable.php';

class SimplePagesPage extends Omeka_Record {
    
    public $user_id;
    public $title = '';
    public $html  = '';
    public $css   = '';
    public $slug  = '';
    public $published_date;
    public $is_published = 0;
        
    /**
     * Use ZF's Zend_Filter_Input mechanism to properly clean all the user input
     *
     * @return void
     **/
    protected function filterInput($input) {
        $options = array('namespace'=>'Omeka_Filter');
        
        unset($input['title']);
        unset($input['html']);
        unset($input['css']);
        unset($input['slug']);
        
        $filters = array('*' => 'StringTrim');
        $filter = new Zend_Filter_Input($filters, null, $input, $options);
        
        return $filter->getUnescaped();
    }
    
    // Gotta have a valid title and slug
    protected function _validate() {        
        if(empty($this->title)) {
            $this->addError('title', 'Pages must have a title.');
        }
        if (empty($this->slug)) {
            $this->addError('title', 'Pages must have a slug.');            
        }
        
        /* to do: check to see if the slug is already being used */
    }
    
    // If the page is a new entry, then pull in and process info before saving
    protected function beforeValidate() {}
    
    protected function beforeInsert() {}
    
    protected function beforeDelete() {}
}