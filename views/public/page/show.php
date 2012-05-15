<?php 

$bodyclass = 'page simple-page';
if (simple_pages_is_home_page(get_current_simple_page())) {
    $bodyclass .= ' simple-page-home';
} ?>

<?php head(array('title' => html_escape(simple_page('title')), 'bodyclass' => $bodyclass, 'bodyid' => html_escape(simple_page('slug')))); ?>
<div id="primary">
    <?php if (!simple_pages_is_home_page(get_current_simple_page())): ?>
    <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
    <?php endif; ?>
    <h1><?php echo html_escape(simple_page('title')); ?></h1>
    <?php
    if (simple_page('use_tiny_mce')) {
        echo simple_page('text');
    } else {
        echo eval('?>' . simple_page('text'));
    }
    ?>
</div>
<?php if (!simple_pages_is_home_page(get_current_simple_page())): ?>
<div id="secondary">
    <?php echo simple_pages_navigation(); ?>
</div>
<?php endif; ?>
<?php echo foot(); ?>