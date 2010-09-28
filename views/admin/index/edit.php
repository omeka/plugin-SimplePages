<?php
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => html_escape('Simple Pages | Edit "' . simple_page('title') . '"'));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
    <?php echo flash(); ?>
    <p>This page was created by <strong><?php echo html_escape(get_current_simple_page()->getCreatedByUser()->username); ?></strong> 
    on <?php echo html_escape(date('M j, Y g:ia', strtotime(simple_page('inserted')))); ?>, and last 
    modified by <strong><?php echo html_escape(get_current_simple_page()->getModifiedByUser()->username); ?></strong> 
    on <?php echo html_escape(date('M j, Y g:ia', strtotime(simple_page('updated')))); ?></p>
    <form method="post">
        <?php include 'form.php'; ?>
        <?php echo $this->formSubmit('simple-pages-edit-submit', 
                                     'Save Page', 
                                     array('id'    => 'simple-pages-edit-submit', 
                                           'class' => 'submit submit-medium')); ?>
    </form>
    <?php echo delete_button(null, 'delete-page', 'Delete this Page'); ?>
</div>
<?php foot(); ?>
