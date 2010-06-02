<?php head(array('title' => html_escape(simple_page('title')), 'bodyclass' => 'page', 'bodyid' => html_escape(simple_page('slug')))); ?>
<div id="primary">
    <h1><?php echo html_escape(simple_page('title')); ?></h1>
    <?php echo eval('?>' . simple_page('text')); ?>
    
    <?php 
    $childPageLinks = get_links_for_children_simple_pages();    
    if ($childPageLinks):
        echo '<ul id="simple-pages-page-children-nav" class="navigation">';
        echo nav($childPageLinks); 
        echo'</ul>';
    endif;
    ?>
</div>
<?php echo foot(); ?>