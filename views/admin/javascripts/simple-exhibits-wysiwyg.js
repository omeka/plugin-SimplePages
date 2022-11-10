jQuery(document).ready(function() {
    var selector;
    if (jQuery('#simple-exhibits-use-tiny-mce').is(':checked')) {
        selector = '#simple-exhibits-content';
    } else {
        selector = false;
    }
    Omeka.wysiwyg({
        selector: selector,
        menubar: 'edit view insert format table',
        plugins: 'lists link code paste media autoresize image table charmap hr',
        browser_spellcheck: true
    });
    var selector;
    if (jQuery('#simple-exhibits-use-tiny-mce').is(':checked')) {
        selector = '#simple-exhibits-text';
    } else {
        selector = false;
    }
    Omeka.wysiwyg({
        selector: selector,
        menubar: 'edit view insert format table',
        plugins: 'lists link code paste media autoresize image table charmap hr',
        browser_spellcheck: true
    });

    // Add or remove TinyMCE control.
    jQuery('#simple-exhibits-use-tiny-mce').click(function() {
        if (jQuery(this).is(':checked')) {
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'simple-exhibits-content');
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'simple-exhibits-text');
        } else {
            tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'simple-exhibits-content');
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'simple-exhibits-text');
        }
    });
});
