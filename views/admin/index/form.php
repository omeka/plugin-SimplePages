<div class="field">
    <?php echo $this->formLabel('title', 'Title'); ?>
    <div class="inputs">
        <?php echo $this->formText('title', 
                                   simple_page('title'), 
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
                                   simple_page('slug'), 
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
                                       simple_page('text'), 
                                       array('id'    => 'simple-pages-text', 
                                             'class' => 'textinput', 
                                             'cols'  => 66, 
                                             'rows'  => 20)); ?>
        <p class="explanation">The content for the page (optional). HTML markup and 
        PHP code are allowed.</p>    
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('parent_id', 'Parent'); ?>
    <div class="inputs">    
        <?php echo simple_pages_select_parent_page(get_current_simple_page()); ?> 
        <p class="explanation">The parent page.</p>    
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('order', 'Order'); ?>
    <div class="inputs">
        <?php echo $this->formText('order', 
                                   simple_page('order'), 
                                   array('id'    => 'simple-pages-text', 
                                             'class' => 'textinput')); ?>
        <p class="explanation">The order of the page relative to the other pages with the same parent page.</p>    
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('add_to_public_nav', 'Link to this page on the public navigation?'); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('add_to_public_nav', 
                                       simple_page('add_to_public_nav'), 
                                       array('id' => 'simple-pages-add-to-public-nav'), 
                                       array(1, 0)); ?>
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('is_home_page', 'Make this page the home page?'); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('is_home_page', 
                                       (int) simple_pages_is_home_page(get_current_simple_page()), 
                                       array('id' => 'simple-pages-is-home-page'), 
                                       array(1, 0)); ?>
    </div>
</div>

<div class="field">
    <?php echo $this->formLabel('is_published', 'Publish this page?'); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('is_published', 
                                       simple_page('is_published'), 
                                       array('id' => 'simple-pages-is-published'), 
                                       array(1, 0)); ?>
    </div>
</div>