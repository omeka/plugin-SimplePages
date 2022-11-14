console.log('before jquery');
jQuery(document).ready(function() {
    var selector;
    console.log('inside 1st jquery');
    if (jQuery('#simple-exhibits-text-use-tiny-mce').is(':checked')) {
        selector = '#simple-exhibits-text';
        console.log('simple exhibits text checked');
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
    jQuery('#simple-exhibits-text-use-tiny-mce').click(function() {
        if (jQuery(this).is(':checked')) {
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'simple-exhibits-text');
        } else {
            tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'simple-exhibits-text');
        }
    });
});

jQuery(document).ready(function() {
    var selector;
    console.log('inside 2nd jquery');
    if (jQuery('#simple-exhibits-content-use-tiny-mce').is(':checked')) {
        selector = '#simple-exhibits-content';
        console.log('simple echibits content checked');
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
    jQuery('#simple-exhibits-content-use-tiny-mce').click(function() {
        if (jQuery(this).is(':checked')) {
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'simple-exhibits-content');
        } else {
            tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'simple-exhibits-content');
        }
    });

});



