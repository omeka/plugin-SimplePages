<?php echo js('tiny_mce/tiny_mce'); ?>
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

<div class="field">
    <?php echo $this->formLabel('title', __('Title')); ?>
    <div class="inputs">
        <?php echo $this->formText('title', 
                                   simple_page('title'), 
                                   array('id'    => 'simple-pages-title', 
                                         'class' => 'textinput', 
                                         'size'  => 40)); ?>
        <p class="explanation"><?php echo __('The title of the page (required).'); ?></p>
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('slug', __('Slug')); ?>
    <div class="inputs">
        <?php echo $this->formText('slug', 
                                   simple_page('slug'), 
                                   array('id'    => 'simple-pages-slug', 
                                         'class' => 'textinput', 
                                         'size'  => 40)); ?>
        <p class="explanation">
            <?php echo __('The URL slug for the page. Automatically created from the title if not entered. Allowed characters: alphanumeric, underscores, dashes, and forward slashes.'); ?>
        </p>
    </div>

</div>

<div class="field">
    <?php echo $this->formLabel('use_tiny_mce', __('Use HTML editor?')); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('use_tiny_mce', 
                                       simple_page('use_tiny_mce'), 
                                       array('id' => 'simple-pages-use-tiny-mce'), 
                                       array(1, 0)); ?>
        <p class="explanation">
            <?php echo __('This will enable an HTML editor for the simple page text below. <strong>Warning</strong>: if enabled, PHP code will not be evaluated and may be hidden from view. Be sure to remove PHP code that you don\'t want exposed in the HTML source.'); ?>
        </p>    
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('text', __('Text')); ?>
    <div class="inputs">
        <?php echo $this->formTextarea('text', 
                                       simple_page('text'), 
                                       array('id'    => 'simple-pages-text', 
                                             'class' => 'textinput', 
                                             'cols'  => 66, 
                                             'rows'  => 35)); ?>
        <p class="explanation">
            <?php echo __('The content for the page (optional). HTML markup is allowed. PHP code is allowed if you are not using the HTML editor.'); ?>
        </p>    
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('parent_id', __('Parent')); ?>
    <div class="inputs">    
        <?php echo simple_pages_select_parent_page(get_current_simple_page()); ?> 
        <p class="explanation"><?php echo __('The parent page.'); ?></p>    
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('order', __('Order')); ?>
    <div class="inputs">
        <?php echo $this->formText('order', 
                                   simple_page('order'), 
                                   array('id'    => 'simple-pages-text', 
                                             'class' => 'textinput')); ?>
        <p class="explanation"><?php echo __('The order of the page relative to the other pages with the same parent.'); ?></p>    
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('add_to_public_nav', __('Add this page to the primary navigation?')); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('add_to_public_nav', 
                                       simple_page('add_to_public_nav'), 
                                       array('id' => 'simple-pages-add-to-public-nav'), 
                                       array(1, 0)); ?>
        <p class="explanation"><?php echo __("Checking this box will add a link to this page to the theme's primary navigation. If custom header navigation is configured in the theme, a link to this page will not appear in the primary navigation unless you also add it to your theme's configuration."); ?></p>
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('is_home_page', __('Make this page the home page?')); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('is_home_page', 
                                       (int) simple_pages_is_home_page(get_current_simple_page()), 
                                       array('id' => 'simple-pages-is-home-page'), 
                                       array(1, 0)); ?>
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('is_published', __('Publish this page?')); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('is_published', 
                                       simple_page('is_published'), 
                                       array('id' => 'simple-pages-is-published'), 
                                       array(1, 0)); ?>
        <p class="explanation"><?php echo __('Checking this box will make the page public and it will appear in Simple Page navigation.'); ?></p>
    </div>
</div>
