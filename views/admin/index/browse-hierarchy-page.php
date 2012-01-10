<?php
/*
This is a hack.  
Apparently, $this !== __v().
So $this->simplePage != __v()->simplePage 
So when the subsequent helper functions try to get the current simple page, they would not find them, 
Unless we explicitly set the current simple page.
If you try to fix this, see simple_pages_display_hierarchy, especially the call to __v()->partial
*/
set_current_simple_page($this->simplePage);
?>

<p><a href="<?php echo html_escape(public_uri(simple_page('slug'))); ?>">
<?php echo html_escape(simple_page('title')); ?></a> 
 (<?php echo html_escape(simple_page('slug')); ?>)<br/> 
 <?php echo __('<strong>%1$s</strong> on %2$s',
                html_escape(get_current_simple_page()->getModifiedByUser()->username),
                html_escape(format_date(simple_page('updated'), Zend_Date::DATETIME_MEDIUM))); ?>
 <a class="edit" href="<?php echo html_escape(uri("simple-pages/index/edit/id/" . simple_page('id'))) ?>"><?php echo __('Edit'); ?></a><br/>
 <?php echo (simple_page('is_published') ? __('Published') : __('Not Published')); ?>
</p>
