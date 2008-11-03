<?php
class SimplePagesPage extends Omeka_Record
{
    public $created_by_user_id;
    public $published = 0;
    public $title;
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
	    $this->title = trim($this->title);
	    $this->slug = trim($this->slug);
	    
		if (empty($this->slug)) {
			$this->slug = generate_slug($this->title);
		}
	}
    
    protected function _validate()
    {
        if (empty($this->title)) {
			$this->addError('title', 'The page must be given a title.');
		}        
		
		if (255 < strlen($this->title)) {
			$this->addError('title', 'The title for your page must be 255 characters or less.');
		}
        
		if (empty($this->slug)) {
			$this->addError('slug', 'The page must be given a valid slug.');
		}
        
		if (255 < strlen($this->slug)) {
			$this->addError('slug', 'The slug for your page must be 255 characters or less.');
		}
        
		if (!$this->fieldIsUnique('slug')) {
			$this->addError('slug', 'Your slug is already in use by another page. Please choose another.');
		}
    }
    
    protected function beforeSaveForm($post)
    {
		$this->created_by_user_id = current_user()->id;
    }
}