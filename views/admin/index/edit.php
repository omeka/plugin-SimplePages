<?php
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => html_escape(__('Simple Pages | Edit "%s"', simple_page('title'))));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
    <?php echo flash(); ?>
    <p><?php echo __('This page was created by <strong>%1$s</strong> on %2$s, and last modified by <strong>%3$s</strong> on %4$s.',
        html_escape(get_current_simple_page()->getCreatedByUser()->username),
        html_escape(format_date(simple_page('inserted'), Zend_Date::DATETIME_MEDIUM)),
        html_escape(get_current_simple_page()->getModifiedByUser()->username), 
        html_escape(format_date(simple_page('updated'), Zend_Date::DATETIME_MEDIUM))); ?></p>
    <form method="post">
        <?php include 'form.php'; ?>
        <?php echo $this->formSubmit('simple-pages-edit-submit', 
                                     __('Save Page'), 
                                     array('id'    => 'simple-pages-edit-submit', 
                                           'class' => 'submit submit-medium')); ?>
    </form>
    <?php echo delete_button(null, 'delete-page', __('Delete this Page')); ?>
</div>
<?php foot(); ?>
