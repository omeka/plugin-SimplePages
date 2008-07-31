<?php head(array('title' => 'SimplePages', 'body_class' => 'simple-pages-plugin')); ?>

<div id="primary">
	<?php
		if (!empty($page)) {
	?>
	<div id="simple_pages_page">
	<?php
		if ($page['is_published']) {
	?>	
		<style type="text/css"><?php echo $page['css'] ?></style> 
	<?php		
			echo $page['html'];	
		}	
	?>
	</div>
	<?php
		} else {
	?>
		<p>This page is unavailable.</p>
	<?php 
		}
	?>	
</div>


<?php foot(); ?>