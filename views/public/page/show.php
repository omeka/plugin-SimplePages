<?php head(array('title' => htmlspecialchars($page->title), 'bodyclass' => 'page', 'bodyid' => $page->slug)); ?>
<div id="primary">
    <h1><?php echo htmlspecialchars($page->title); ?></h1>
    <?php echo eval('?>' . $page->text); ?>
</div>
<?php echo foot(); ?>