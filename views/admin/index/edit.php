<?php


queue_js_file('tiny_mce/tiny_mce');
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => html_escape(__('Simple Pages | Edit "%s"', simple_page('title'))));
echo head($head);
?>


<script type="text/javascript">
jQuery(window).load(function() {
    // Initialize and configure TinyMCE.
    tinyMCE.init({
        // Assign TinyMCE a textarea:
        mode : 'exact',
        elements: '<?php if (simple_page('use_tiny_mce')) echo 'simple-pages-text'; ?>',
        // Add plugins:
        plugins: 'media,paste,inlinepopups',
        // Configure theme:
        theme: 'advanced',
        theme_advanced_toolbar_location: 'top',
        theme_advanced_toolbar_align: 'left',
        theme_advanced_buttons3_add : 'pastetext,pasteword,selectall',
        // Allow object embed. Used by media plugin
        // See http://www.tinymce.com/forum/viewtopic.php?id=24539
        media_strict: false,
        // General configuration:
        convert_urls: false,
    });
    // Add or remove TinyMCE control.
    jQuery('#simple-pages-use-tiny-mce').click(function() {
        if (jQuery(this).is(':checked')) {
            tinyMCE.execCommand('mceAddControl', true, 'simple-pages-text');
        } else {
            tinyMCE.execCommand('mceRemoveControl', true, 'simple-pages-text');
        }
    });
});
</script>

<?php echo flash(); ?>
<p><?php echo __('This page was created by <strong>%1$s</strong> on %2$s, and last modified by <strong>%3$s</strong> on %4$s.',
    html_escape(get_current_simple_page()->getCreatedByUser()->username),
    html_escape(format_date(simple_page('inserted'), Zend_Date::DATETIME_MEDIUM)),
    html_escape(get_current_simple_page()->getModifiedByUser()->username), 
    html_escape(format_date(simple_page('updated'), Zend_Date::DATETIME_MEDIUM))); ?></p>
<?php include 'form.php'; ?>
<?php echo new SimplePagesAdminForm(array('record' => get_current_record('simple_pages_page'))); ?>
<?php echo foot(); ?>
