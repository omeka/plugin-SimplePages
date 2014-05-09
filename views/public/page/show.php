<?php echo head(array(
    'title' => metadata('simple_pages_page', 'title'),
    'bodyclass' => 'page simple-page',
    'bodyid' => metadata('simple_pages_page', 'slug')
)); ?>
<div id="primary">
    <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
    <h1><?php echo metadata('simple_pages_page', 'title'); ?></h1>
    <?php
    $text = metadata('simple_pages_page', 'text', array('no_escape' => true));
    echo $this->shortcodes($text);
    ?>
</div>

<?php echo foot(); ?>
