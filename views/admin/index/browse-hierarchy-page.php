<p><a href="<?php echo html_escape(public_uri($page->slug)); ?>">
<?php echo html_escape($page->title); ?></a> 
 (<?php echo html_escape($page->slug); ?>)<br/> 
 <strong><?php echo html_escape($page->getModifiedByUser()->username); ?></strong> on <?php echo html_escape(date('M j, Y g:ia', strtotime($page->updated))); ?>
 <a class="edit" href="<?php echo html_escape(uri("simple-pages/index/edit/id/$page->id")) ?>">Edit</a><br/>
    <?php if ($page->is_published): ?>
     Published
    <?php else: ?>
     Not Published
    <?php endif; ?>
</p>