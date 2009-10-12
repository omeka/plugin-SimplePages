<?php
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => html_escape('Simple Pages | Edit "' . $page->title . '"'));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
    <?php echo flash(); ?>
    <p>This page was created by <strong><?php echo html_escape($page->getCreatedByUser()->username); ?></strong> 
    on <?php echo html_escape(date('M j, Y g:ia', strtotime($page->inserted))); ?>, and last 
    modified by <strong><?php echo html_escape($page->getModifiedByUser()->username); ?></strong> 
    on <?php echo html_escape(date('M j, Y g:ia', strtotime($page->updated))); ?></p>
    <form method="post">
        <?php include 'form.php'; ?>
        <?php echo $this->formSubmit('simple-pages-edit-submit', 
                                     'Save Page', 
                                     array('id'    => 'simple-pages-edit-submit', 
                                           'class' => 'submit submit-medium')); ?>
        <p id="simple-pages-delete">
            <a class="delete" href="<?php echo html_escape(uri("simple-pages/index/delete/id/$page->id")); ?>">Delete This Page</a>
        </p>
    </form>
</div>
<?php foot(); ?>
