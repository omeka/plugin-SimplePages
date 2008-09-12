<?php head(array('title' => 'SimplePages', 'body_class' => 'primary simple-pages-plugin')); ?>
<h1>SimplePages | Add Page</h1>
<?php echo flash(); ?>

<div id="primary">
    <?php echo simple_pages_update_slug_javascript(); ?>
    <form id="simple-pages-form" method="post">
        <div class="field">
            <label for="simple_pages_page_title">Title</label>
            <div class="input">
            <input type="simple_pages_page_title" class="textinput" name="simple_pages_page_title" id="simple_pages_page_title" value="" onkeyup="updateSlug();" />
            </div>
        </div>
        <div class="field">
            <label for="simple_pages_page_slug">Slug</label>
            <div class="input">
            <input type="simple_pages_page_slug" class="textinput" name="simple_pages_page_slug" id="simple_pages_page_slug" value="" />
            </div>
            <p class="explanation">Edit the URL slug for your page. Defaults to the title of your page.</p>
        </div>
        <div class="field">
            <label for="simple_pages_page_html">Content</label>
            <div class="input">
                <?php echo textarea(array('id'=>'simple_pages_page_html','class'=>'textinput html-editor','name'=>'simple_pages_page_html','rows'=>'20','cols'=>'80'), null); ?>
            </div>
        </div>
        <div class="field">
            <label for="simple_pages_page_css">Styles</label>
            <div class="input">
            <?php echo textarea(array('id'=>'simple_pages_page_css','class'=>'textinput','name'=>'simple_pages_page_css','rows'=>'20','cols'=>'80'), $page['css']); ?>
            </div>
            <p class="explanation">Add custom CSS to your page.</p>
        </div>
        <div class="field checkbox">
            <div class="input checkbox">
                <label for="simple_pages_page_is_published">Public</label>
                
            <?php echo checkbox(array('name'=>'simple_pages_page_is_published', 'id'=>'simple_pages_page_is_published'), TRUE, null); ?> 
            </div>           
        </div>
        <p id="submits"><?php echo submit('Save',array('class'=>'submit submit-medium')); ?></p>
    </form>
</div>
<?php foot(); ?>
