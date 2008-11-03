<?php
class SimplePagesPage extends Omeka_Record
{
    public $modified_by_user_id;
    public $published = 0;
    public $title;
    public $slug;
    public $text = null;
    public $updated;
    public $inserted;
    
    public function getModifiedByUser()
    {
        return $this->getTable('User')->find($this->modified_by_user_id);
    }
    
	protected function beforeValidate()
	{
	    $this->title = trim($this->title);
	    $this->slug = $this->_generateSlug($this->slug);
	    
		if (empty($this->slug)) {
			$this->slug = $this->_generateSlug($this->title);
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
		
		if ('/' == $this->slug) {
		    $this->addError('slug', 'The slug for your page must not be a forward slash.');
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
		$this->modified_by_user_id = current_user()->id;
		$this->updated = date('Y-m-d H:i:s');
    }
    
    private function _generateSlug($seed)
    {
        $seed = trim($seed);
        $seed = strtolower($seed);
        // Replace spaces with dashes.
        $seed = str_replace(' ', '-', $seed);
        // Remove all but alphanumeric characters and underscores.
        return preg_replace('/[^\w\/-]/i', '', $seed);
    }
}