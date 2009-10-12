<?php head(array('title' => html_escape($page->title), 'bodyclass' => 'page', 'bodyid' => html_escape($page->slug))); ?>
<div id="primary">
    <h1><?php echo html_escape($page->title); ?></h1>
    <?php echo eval('?>' . $page->text); ?>
</div>
<?php echo foot(); ?>