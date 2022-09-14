<?php
queue_css_file('simple-exhibits');
queue_js_file('vendor/tinymce/tinymce.min');
queue_js_file('simple-exhibits-wysiwyg');
$head = array('bodyclass' => 'simple-exhibits primary', 
              'title' => html_escape(__('Simple Exhibits | Add Page')));
echo head($head);
?>

<?php echo flash(); ?>
<?php echo $form; ?>
<?php echo foot(); ?>
