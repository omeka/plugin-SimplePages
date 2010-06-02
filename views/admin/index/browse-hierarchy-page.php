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
 <strong><?php echo html_escape(get_current_simple_page()->getModifiedByUser()->username); ?></strong> on <?php echo html_escape(date('M j, Y g:ia', strtotime(simple_page('updated')))); ?>
 <a class="edit" href="<?php echo html_escape(uri("simple-pages/index/edit/id/" . simple_page('id'))) ?>">Edit</a><br/>
    <?php if (simple_page('is_published')): ?>
     Published
    <?php else: ?>
     Not Published
    <?php endif; ?>
</p>