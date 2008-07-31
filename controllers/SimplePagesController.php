<?php
/**
 * SimplePagesController
 * @package: SimplePages
 */
 
class SimplePagesController extends Omeka_Controller_Action {	
	
	public function init() 
	{
	}
	
	public function indexAction() 
	{
		$this->browseAction();
	}
		
	public function browseAction() 
	{		
		if ($_POST) {
			$selectedPages = $this->getSelectedPages($_POST);
			
			$selectedAction = strtolower($_POST['simple_pages_selected_action']);
			switch($selectedAction) {
				case 'delete':
					foreach($selectedPages as $page) {
						$page->delete();
					}
				break;
				
				case 'hide':
					foreach($selectedPages as $page) {
						$page->is_published = 0;
						$page->save();
					}
				break;
				
				case 'publish':
					foreach($selectedPages as $page) {
						$page->is_published = 1;
						$page->save();
					}
				break;
					
				default:
			}	
		}
		
		$pages =  $this->getTable('SimplePagesPage')->findRecent();
		return $this->render('simple-pages/browse.php', compact("pages"));
	}
	
	protected function getSelectedPages($post)
	{
		$pages = array();
		$pageNum = 0;
						
		for($i = 0; $i < $post['simple_pages_page_count']; $i++)
		{
			if (isset($post['simple_pages_selected_page_' . $i])) {
				$pageId = $post['simple_pages_selected_page_' . $i];
				$pages[] = $this->getTable('SimplePagesPage')->find($pageId);				
			}	
		}
		return $pages;
	}
	
	public function addPageAction() 
	{
		
		//add a page
		
		if ($_POST) {
			try {	
				$page = new SimplePagesPage();
				$page->user_id = current_user()->id;
				$page->published_date = date('Y-m-d H:i:s');
				$page->title = $_POST['simple_pages_page_title'];
				$page->html = $_POST['simple_pages_page_html'];
				$page->css = $_POST['simple_pages_page_css'];
				$page->slug = simple_pages_clean_path($_POST['simple_pages_page_slug']);
				$page->is_published = $_POST['simple_pages_page_is_published'] ? 1 : 0;
				$page->save();
			} catch (Exception $e) {
				//print_r($e);
				throw $e;
			}
			$this->_redirect('simple-pages/browse/');
		}
		return $this->render('simple-pages/add-page.php');	
	}
	
	public function deletePageAction() 
	{
		$pageId = $this->_getParam('id');
		$page = $this->getTable('SimplePagesPage')->find($pageId);
		$page->delete();
		$this->_redirect('simple-pages/browse/');		
	}
	
	public function showPageAction() 
	{
				
		$pageId = $this->_getParam('id');		
		$page = $this->getTable('SimplePagesPage')->find($pageId);
		
		return $this->render('simple-pages/show-page.php', compact("page"));			
	}
	
	public function editPageAction() 
	{
        $pageId = $this->_getParam('id');
		$page = $this->getTable('SimplePagesPage')->find($pageId);
		
		if ($_POST) {
			if (!empty($_POST['delete'])) {
				$page->delete();
				$this->_redirect('simple-pages/browse/');
			} else {
				try {	
					$page->user_id = current_user()->id;
					$page->published_date = date('Y-m-d H:i:s');
					$page->title = $_POST['simple_pages_page_title'];
					$page->slug = simple_pages_clean_path($_POST['simple_pages_page_slug']);
					$page->html = $_POST['simple_pages_page_html'];
					$page->css = $_POST['simple_pages_page_css'];
					$page->is_published = $_POST['simple_pages_page_is_published'] ? 1 : 0;
					$page->save();
				} catch (Exception $e) {
					//print_r($e);
					throw $e;
				}
				$this->_redirect('simple-pages/browse/');
			}
		}
		
		return $this->render('simple-pages/edit-page.php', compact("page"));			
	}

}
