<?php head(array('title' => $page->title)); ?>

<div id="primary">
	<h1><?php echo $page->title; ?></h1>
	<?php echo eval('?>' . $page->text); ?>
</div>

<?php echo foot(); ?>