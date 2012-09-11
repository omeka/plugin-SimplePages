<?php
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => html_escape(__('Simple Pages | Edit "%s"', simple_page('title'))));
head($head);
?>
<?php echo flash(); ?>
<p><?php echo __('This page was created by <strong>%1$s</strong> on %2$s, and last modified by <strong>%3$s</strong> on %4$s.',
    html_escape(get_current_simple_page()->getCreatedByUser()->username),
    html_escape(format_date(simple_page('inserted'), Zend_Date::DATETIME_MEDIUM)),
    html_escape(get_current_simple_page()->getModifiedByUser()->username), 
    html_escape(format_date(simple_page('updated'), Zend_Date::DATETIME_MEDIUM))); ?></p>
<form method="post">
    <div class="seven columns alpha">
        <?php include 'form.php'; ?>
    </div>

    <div class="three columns omega panel" id="save">
        <?php echo $this->formSubmit('simple-pages-edit-submit',
            __('Save Page'), 
            array('id'    => 'simple-pages-edit-submit', 
            'class' => 'submit big green button')); ?>
        <a href="<?php echo url(array('action' => 'delete-confirm')); ?>" class="big red button delete-confirm"><?php echo __('Delete'); ?></a>
    </div>
</form>
<?php foot(); ?>
