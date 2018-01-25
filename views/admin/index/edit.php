<?php
queue_js_file('vendor/tinymce/tinymce.min');
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => __('Simple Pages | Edit "%s"', metadata('simple_pages_page', 'title')));
echo head($head);
?>

<script type="text/javascript">
jQuery(document).ready(function() {
    Omeka.wysiwyg({
        selector: <?php echo json_encode($simple_pages_page->use_tiny_mce ? '#simple-pages-text' : false); ?>,
    });
    // Add or remove TinyMCE control.
    jQuery('#simple-pages-use-tiny-mce').click(function() {
        if (jQuery(this).is(':checked')) {
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'simple-pages-text');
        } else {
            tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'simple-pages-text');
        }
    });
});
</script>

<?php echo flash(); ?>
<p><?php echo __('This page was created by <strong>%1$s</strong> on %2$s, and last modified by <strong>%3$s</strong> on %4$s.',
    metadata('simple_pages_page', 'created_username'),
    html_escape(format_date(metadata('simple_pages_page', 'inserted'), Zend_Date::DATETIME_SHORT)),
    metadata('simple_pages_page', 'modified_username'), 
    html_escape(format_date(metadata('simple_pages_page', 'updated'), Zend_Date::DATETIME_SHORT))); ?></p>
<?php echo $form; ?>
<?php echo foot(); ?>
