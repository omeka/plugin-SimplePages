<?php

queue_js_file('vendor/tinymce/tinymce.min');
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => html_escape(__('Simple Pages | Add Page')));
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
<?php echo $form; ?>
<?php echo foot(); ?>
