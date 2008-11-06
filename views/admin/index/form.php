<div class="field">
    <?php echo $this->formLabel('title', 'Title'); ?>
    <div class="inputs">
        <?php echo $this->formText('title', 
                                   $page->title, 
                                   array('id'    => 'simple-pages-title', 
                                         'class' => 'textinput', 
                                         'size'  => 40)); ?>
        <p class="explanation">The title of the page (required).</p>
    </div>
</div>
<div class="field">
    <?php echo $this->formLabel('slug', 'Slug'); ?>
    <div class="inputs">
        <?php echo $this->formText('slug', 
                                   $page->slug, 
                                   array('id'    => 'simple-pages-slug', 
                                         'class' => 'textinput', 
                                         'size'  => 40)); ?>
        <p class="explanation">The URL slug for the page. Automatically created 
        from the title if not entered. Allowed characters: alphanumeric, 
        underscores, dashes, and forward slashes.</p>
    </div>

</div>
<div class="field">
    <?php echo $this->formLabel('text', 'Text'); ?>
    <div class="inputs">
        <?php echo $this->formTextarea('text', 
                                       $page->text, 
                                       array('id'    => 'simple-pages-text', 
                                             'class' => 'textinput', 
                                             'cols'  => 66, 
                                             'rows'  => 20)); ?>
    <p class="explanation">The content for the page (optional). HTML markup and 
    PHP code are allowed.</p>    
</div>

</div>
<div class="field">
    <?php echo $this->formLabel('is_published', 'Publish this page?'); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('is_published', 
                                       $page->is_published, 
                                       array('id' => 'simple-pages-is-published'), 
                                       array(1, 0)); ?>
    </div>
</div>
<div class="field">
    <?php echo $this->formLabel('add_to_public_nav', 'Link to this page on the public navigation?'); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('add_to_public_nav', 
                                       $page->add_to_public_nav, 
                                       array('id' => 'simple-pages-add-to-public-nav'), 
                                       array(1, 0)); ?>
    </div>
</div>