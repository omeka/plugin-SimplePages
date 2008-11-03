<?php
require_once 'SimplePagesPageTable.php';

class SimplePagesPage extends Omeka_Record
{
    public $created_by_user_id;
    public $published = 0;
    public $title = null;
    public $slug;
    public $text = null;
    public $updated;
    public $inserted;
    
    public function getCreatedByUser()
    {
        return $this->getTable('User')->find($this->created_by_user_id);
    }
    
	protected function beforeValidate()
	{
		if (empty($this->slug)) {
			$this->slug = generate_slug($this->title);
		}
	}
    
    protected function _validate()
    {
        if (empty($this->title)) {
			$this->addError('title', 'The page must be given a title.');
		}        
		
		if (!$this->fieldIsUnique('slug')) {
			$this->addError('slug', 'Your URL slug is already in use by another page. Please choose another.');
		}
    }
    
    protected function beforeSaveForm($post)
    {
		$this->created_by_user_id = current_user()->id;
    }
}