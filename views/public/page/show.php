<?php echo head(array('title' => html_escape(simple_page('title')), 'bodyclass' => 'page simple-page', 'bodyid' => html_escape(simple_page('slug')))); ?>
<div id="primary">
    <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
    <h1><?php echo html_escape(simple_page('title')); ?></h1>
    <?php
    if (simple_page('use_tiny_mce')) {
        echo simple_page('text');
    } else {
        echo eval('?>' . simple_page('text'));
    }
    ?>
</div>
<div id="secondary">
    <?php echo simple_pages_navigation(); ?>
</div>
<?php echo foot(); ?>
