<div class="field">
    <?php echo $this->formLabel('title', 'Title'); ?>
    <div class="inputs">
        <?php echo $this->formText('title', 
                                   $page->title, 
                                   array('id'    => 'simple-pages-title', 
                                         'class' => 'textinput', 
                                         'size'  => 40)); ?>
    </div>
    <p class="explanation">The title of the page (required).</p>
</div>
<div class="field">
    <?php echo $this->formLabel('slug', 'Slug'); ?>
    <div class="inputs">
        <?php echo $this->formText('slug', 
                                   $page->slug, 
                                   array('id'    => 'simple-pages-slug', 
                                         'class' => 'textinput', 
                                         'size'  => 40)); ?>
    </div>
    <p class="explanation">The URL slug for the page. Automatically created 
    from the title if not entered. Allowed characters: alphanumeric, 
    underscores, dashes, and forward slashes.</p>
</div>
<div class="field">
    <?php echo $this->formLabel('text', 'Text'); ?>
    <div class="inputs">
        <?php echo $this->formTextarea('text', 
                                       $page->text, 
                                       array('id'    => 'simple-pages-text', 
                                             'class' => 'textinput', 
                                             'cols'  => 60, 
                                             'rows'  => 20)); ?>
    </div>
    <p class="explanation">The content for the page (optional). HTML and PHP 
    are allowed.</p>
</div>
<div class="field">
    <?php echo $this->formLabel('published', 'Publish this page?'); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('published', 
                                       $page->published, 
                                       array('id' => 'simple-pages-published'), 
                                       array(1, 0)); ?>
    </div>
</div>