<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SimplePages
 */

// Require the Simple Pages table class.
require_once 'SimplePagesPageTable.php';

/**
 * The Simple Pages page record class.
 *
 * @package SimplePages
 * @author CHNM
 * @copyright Center for History and New Media, 2008
 */
class SimplePagesPage extends Omeka_Record
{
    public $modified_by_user_id;
    public $created_by_user_id;
    public $is_published = 0;
    public $add_to_public_nav = 0;
    public $title;
    public $slug;
    public $text = null;
    public $updated;
    public $inserted;
    
    /**
     * Get the modified by user object.
     * 
     * @return User
     */
    public function getModifiedByUser()
    {
        return $this->getTable('User')->find($this->modified_by_user_id);
    }
    
    /**
     * Get the created by user object.
     * 
     * @return User
     */
    public function getCreatedByUser()
    {
        return $this->getTable('User')->find($this->created_by_user_id);
    }

    /**
     * Prepare certain variables for validation.
     */
    protected function beforeValidate()
    {
        $this->title = trim($this->title);
        // Generate the page slug.
        $this->slug = $this->_generateSlug($this->slug);
        // If the resulting slug is empty, generate it from the page title.
        if (empty($this->slug)) {
            $this->slug = $this->_generateSlug($this->title);
        }
    }
    
    /**
     * Validate the form data.
     */
    protected function _validate()
    {
        if (empty($this->title)) {
            $this->addError('title', 'The page must be given a title.');
        }        
        
        if (255 < strlen($this->title)) {
            $this->addError('title', 'The title for your page must be 255 characters or less.');
        }
        
        if (!$this->fieldIsUnique('title')) {
            $this->addError('title', 'The title is already in use by another page. Please choose another.');
        }
        
        if (empty($this->slug)) {
            $this->addError('slug', 'The page must be given a valid slug.');
        }
        
        if (preg_match('/^\/+$/', $this->slug)) {
            $this->addError('slug', 'The slug for your page must not be a forward slash.');
        }
        
        if (255 < strlen($this->slug)) {
            $this->addError('slug', 'The slug for your page must be 255 characters or less.');
        }
        
        if (!$this->fieldIsUnique('slug')) {
            $this->addError('slug', 'The slug is already in use by another page. Please choose another.');
        }
    }
    
    /**
     * Prepare special variables before saving the form.
     */
    protected function beforeSaveForm($post)
    {
        $this->modified_by_user_id = current_user()->id;
        $this->updated = date('Y-m-d H:i:s');
    }
    
    /**
     * Generate a slug given a seed string.
     * 
     * @param string
     * @return string
     */
    private function _generateSlug($seed)
    {
        $seed = trim($seed);
        $seed = strtolower($seed);
        // Replace spaces with dashes.
        $seed = str_replace(' ', '-', $seed);
        // Remove all but alphanumeric characters, underscores, and dashes.
        return preg_replace('/[^\w\/-]/i', '', $seed);
    }
    
    /**
     * Creates and returns a Zend_Search_Lucene_Document for the SimplePagesPage
     *
     * @param Zend_Search_Lucene_Document $doc The Zend_Search_Lucene_Document from the subclass of Omeka_Record.
     * @param string $contentFieldValue The value for the content field.
     * @return Zend_Search_Lucene_Document
     **/
    public function createLuceneDocument($doc=null, $contentFieldValue='') 
    {   
        // If no document, lets create a new Zend Lucene Document
        if (!$doc) {
            $doc = new Zend_Search_Lucene_Document(); 
        }
        
        if ($search = Omeka_Search::getInstance()) {
            
            // adds the fields for added or modified
            $search->addLuceneField($doc, 'Keyword', Omeka_Search::FIELD_NAME_DATE_ADDED, $this->inserted, true);            
            $search->addLuceneField($doc, 'Keyword', Omeka_Search::FIELD_NAME_DATE_MODIFIED, $this->updated, true);

            // adds the fields for public and private       
            $search->addLuceneField($doc, 'Keyword', Omeka_Search::FIELD_NAME_IS_PUBLIC, $this->is_published == '1' ? Omeka_Search::FIELD_VALUE_TRUE : Omeka_Search::FIELD_VALUE_FALSE, true);            

            // Adds fields for title and text
            $search->addLuceneField($doc, 'UnStored', array('SimplePagesPage', 'title'), $this->title);
            $contentFieldValue .= $this->title . "\n";
            
            $search->addLuceneField($doc, 'UnStored', array('SimplePagesPage', 'text'), $this->text);
            $contentFieldValue .= $this->text . "\n";

            // add the collection id of the collection that contains the item
            if ($this->modified_by_user_id) {
                $search->addLuceneField($doc, 'Keyword', array('SimplePagesPage','modified_by_user_id'), $this->modified_by_user_id, true);                        
            }

            // add the item type id for the item
            if ($this->created_by_user_id) {
                $search->addLuceneField($doc, 'Keyword', array('SimplePagesPage','created_by_user_id'), $this->created_by_user_id, true);                        
            }
        }
     
        return parent::createLuceneDocument($doc, $contentFieldValue);
    }
}