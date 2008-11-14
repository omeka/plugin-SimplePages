<?php head(array('title' => htmlspecialchars($page->title))); ?>
<div id="primary">
    <h1><?php echo htmlspecialchars($page->title); ?></h1>
    <?php echo eval('?>' . $page->text); ?>
</div>
<?php echo foot(); ?>