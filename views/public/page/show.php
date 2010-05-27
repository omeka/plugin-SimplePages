<?php head(array('title' => html_escape($page->title), 'bodyclass' => 'page', 'bodyid' => html_escape($page->slug))); ?>
<div id="primary">
    <h1><?php echo html_escape($page->title); ?></h1>
    <?php echo eval('?>' . $page->text); ?>
    
    
    <?php 
    $childPageLinks = simple_pages_get_page_links($page->id);
    
    if ($childPageLinks){
    echo '<ul id="simple-pages-page-children-nav" class="navigation">';
    echo nav($childPageLinks); 
    echo'</ul>';
}
    ?>
</div>
<?php echo foot(); ?>