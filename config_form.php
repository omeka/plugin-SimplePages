<div class="field">
    <label for="simple_pages_filter_page_content"><?php echo __('Filter User Input For Page Content?'); ?></label>
    <div class="inputs">
    <?php echo __v()->formCheckbox('simple_pages_filter_page_content', true, 
    array('checked'=>(boolean)get_option('simple_pages_filter_page_content'))); ?>
    <p class="explanation">
    <?php
        echo __('If checked, Simple Pages will attempt to filter the '
        . 'HTML provided for the content of pages using the default settings from ' 
        . 'the HtmlPurifier plugin. Note that this will not '
        . 'work unless the HtmlPurifier plugin has also been installed and '
        . 'activated. PHP code will not be allowed in the content of '
        . 'pages if this box is checked.'); ?>
    </p>
    </div>
</div>

