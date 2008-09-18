<?php head(array('title' => 'SimplePages', 'body_class' => 'simple-pages-plugin')); ?>
<h1>SimplePages | Edit Page</h1>
<?php echo flash(); ?>
<div id="primary">

    <?php echo simple_pages_update_slug_javascript(); ?>

    <ul id="panel-nav">
    <?php echo nav(array('Browse Pages' => url_for('simple-pages/browse'), 'View this Page' => simple_pages_slug_url($page))); ?>
    </ul>
        <form id="simple-pages-form" method="post">
        <div class="field">
            <label for="simple_pages_page_title">Title</label>
            <div class="input">
                <input type="simple_pages_page_title" class="textinput" name="simple_pages_page_title" id="simple_pages_page_title" value="<?php echo $page['title'] ?>" onkeyup="updateSlug();" />
            </div>
        </div>
        <div class="field">
                <label for="simple_pages_page_slug">Slug</label>
                <div class="input">
                    <input type="simple_pages_page_slug" class="textinput" name="simple_pages_page_slug" id="simple_pages_page_slug" value="<?php echo $page['slug'] ?>" />
                </div>
        </div>
        <div class="field">
            <label for="simple_pages_page_html">Content</label>
            <div class="input">
            <?php echo textarea(array('id'=>'simple_pages_page_html','name'=>'simple_pages_page_html','rows'=>'20','cols'=>'80'), $page['html']); ?>
            </div>
        </div>
        <div class="field">
            <label for="simple_pages_page_css">Styles</label>
            <div class="input">
                <?php echo textarea(array('id'=>'simple_pages_page_css','name'=>'simple_pages_page_css','rows'=>'20','cols'=>'80'), $page['css']); ?>
            </div>
        </div>
        <div class="field checkbox">
            <label for="simple_pages_page_is_published">Published</label>
            <div class="input checkbox"><?php echo checkbox(array('name'=>'simple_pages_page_is_published', 'id'=>'simple_pages_page_is_published'), $page['is_published'] ? TRUE : FALSE, null); ?></div>
        </div>
        <p id="submits">
        <?php echo submit('Save'); ?>
        <?php echo submit('Delete','delete'); ?>
        </p>
    </form>
</div>
<?php foot(); ?>
