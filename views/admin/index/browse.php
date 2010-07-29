<?php
$head = array('bodyclass' => 'simple-pages primary', 'title' => html_escape('Simple Pages | Browse'), 'content_class' => 'horizontal-nav');
head($head);
?>
<h1><?php echo $head['title']; ?></h1>

<ul id="section-nav" class="navigation">
    <li class="<?php if (isset($_GET['view']) &&  $_GET['view'] != 'hierarchy') {echo 'current';} ?>">
        <a href="<?php echo html_escape(uri('simple-pages/index/browse?view=list')); ?>">List View</a>
    </li>
    <li class="<?php if (isset($_GET['view']) && $_GET['view'] == 'hierarchy') {echo 'current';} ?>">
        <a href="<?php echo html_escape(uri('simple-pages/index/browse?view=hierarchy')); ?>">Hierarchy View</a>
    </li>
</ul>

<p id="add-page" class="add-button"><a class="add" href="<?php echo html_escape(uri('simple-pages/index/add')); ?>">Add a Page</a></p>
<div id="primary">
<?php echo flash(); ?>
<?php if (!has_simple_pages_for_loop()): ?>
    <p>There are no pages. Why not <a href="<?php echo html_escape(uri('simple-pages/index/add')); ?>">add one</a>?</p>
<?php else: ?>
    <?php if (isset($_GET['view']) && $_GET['view'] == 'hierarchy'): ?>
        <?php echo $this->partial('index/browse-hierarchy.php', array('simplePages' => get_simple_pages_for_loop())); ?>
    <?php else: ?>
        <?php echo $this->partial('index/browse-list.php', array('simplePages' => get_simple_pages_for_loop())); ?>
    <?php endif; ?>    
<?php endif; ?>
</div>
<?php foot(); ?>