<?php head(array('title' => html_escape(simple_page('title')), 'bodyclass' => 'page simple-pages', 'bodyid' => html_escape(simple_page('slug')))); ?>
<div id="primary">
    <h1><?php echo html_escape(simple_page('title')); ?></h1>
    <?php echo eval('?>' . simple_page('text')); ?>
</div>
<div id="secondary">
    <?php echo simple_pages_navigation(); ?>
</div>
<?php echo foot(); ?>