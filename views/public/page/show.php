<?php
$bodyclass = 'page simple-exhibit';
if ($is_home_page):
    $bodyclass .= ' simple-exhibit-home';
endif;

echo head(array(
    'title' => metadata('simple_exhibits_page', 'title'),
    'bodyclass' => $bodyclass,
    'bodyid' => metadata('simple_exhibits_page', 'slug')
));
?>
<div id="primary">
    <?php if (!$is_home_page): ?>
    <p id="simple-exhibits-breadcrumbs"><?php echo simple_exhibits_display_breadcrumbs(); ?></p>
    <h1><?php echo metadata('simple_exhibits_page', 'title'); ?></h1>
    <?php endif; ?>
    <?php
    $text = metadata('simple_exhibits_page', 'text', array('no_escape' => true));
    $content = metadata('simple_exhibits_page', 'content', array('no_escape' => true));
    echo $text;
    echo $content; //displays content block
    ?>
</div>

<?php echo foot(); ?>
