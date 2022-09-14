<?php
queue_css_file('simple-exhibits');
queue_js_file('vendor/tinymce/tinymce.min');
queue_js_file('simple-exhibits-wysiwyg');
$head = array('bodyclass' => 'simple-exhibits primary', 
              'title' => __('Simple Exhibits | Edit "%s"', metadata('simple_exhibits_page', 'title')));
echo head($head);
?>

<?php echo flash(); ?>
<p><?php echo __('This page was created by <strong>%1$s</strong> on %2$s, and last modified by <strong>%3$s</strong> on %4$s.',
    metadata('simple_exhibits_page', 'created_username'),
    html_escape(format_date(metadata('simple_exhibits_page', 'inserted'), Zend_Date::DATETIME_SHORT)),
    metadata('simple_exhibits_page', 'modified_username'), 
    html_escape(format_date(metadata('simple_exhibits_page', 'updated'), Zend_Date::DATETIME_SHORT))); ?></p>
<?php echo $form; ?>
<?php echo foot(); ?>
