<?php
queue_css_file('simple-exhibits');
$head = array('bodyclass' => 'simple-exhibits browse',
              'title' => html_escape(__('Simple Exhibits | Browse')),
              'content_class' => 'horizontal-nav');
$hierarchy = false;
if (isset($_GET['view'])) {
	$hierarchy = $_GET['view'] == 'hierarchy';
}
echo head($head);
?>
<ul id="section-nav" class="navigation">
    <li class="<?php if (!$hierarchy) {echo 'current';} ?>">
        <a href="<?php echo html_escape(url('simple-exhibits/index/browse?view=list')); ?>"><?php echo __('List View'); ?></a>
    </li>
    <!-- No hierarchy tab needed 
    <li class="<?php if ($hierarchy) {echo 'current';} ?>">
        <a href="<?php echo html_escape(url('simple-exhibits/index/browse?view=hierarchy')); ?>"><?php echo __('Hierarchy View'); ?></a>
    </li> -->
</ul>
<?php echo flash(); ?>

<a class="add-page button green" href="<?php echo html_escape(url('simple-exhibits/index/add')); ?>"><?php echo __('Add a Page'); ?></a>
<?php if (!has_loop_records('simple_exhibits_page')): ?>
    <p><?php echo __('There are no pages.'); ?> <a href="<?php echo html_escape(url('simple-exhibits/index/add')); ?>"><?php echo __('Add a page.'); ?></a></p>
<?php else: ?>
    <?php if ($hierarchy): ?>
        <?php echo $this->partial('index/browse-hierarchy.php', array('SimpleExhibits' => $simple_exhibits_pages)); ?>
    <?php else: ?>
        <?php echo $this->partial('index/browse-list.php', array('SimpleExhibits' => $simple_exhibits_pages)); ?>
    <?php endif; ?>    
<?php endif; ?>
<?php echo foot(); ?>
