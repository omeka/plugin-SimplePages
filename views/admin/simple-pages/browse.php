<?php head(array('title' => 'SimplePages', 'body_class' => 'simple-pages-plugin')); ?>

<div id="primary">
	<h1>SimplePages | Browse</h1>
	<?php echo flash(); ?>
	<p><a href="<?php echo WEB_DIR; ?>/simple-pages/addPage">Add Page</a></p>
	<h2>SimplePages</h2>
    <script language="javascript" type="text/javascript"> 
		function simple_pages_verify_action() {
			if ($('simple_pages_selected_action').options[$('simple_pages_selected_action').selectedIndex].value == 'delete') {
				if (confirm('Are you sure you want to delete these pages?')) {
					$('simple_pages_browse_form').submit();
				}
			} else {
				$('simple_pages_browse_form').submit();				
			}
		}
	</script>
	<?php
		if (!empty($pages)) {
	?>
	<form id="simple_pages_browse_form" method="post" action="">
	<table>
			<thead>
				<tr>
					<th></th>
					<th>Date</th>
					<th>Title</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
	<?php
			$pageCount = 0;
			foreach($pages as $p) {
				$is_published = $p['is_published'] ? 'Published' : 'Hidden';
				
				echo '<tr>';
				echo '<td>';
				
				checkbox(array('name' => 'simple_pages_selected_page_' . $pageCount, 'id' => 'simple_pages_selected_page_' . $pageCount), FALSE,  $p['id'] , null );
				
				echo '</td>';
				echo '<td>' . date("n/d/Y, g:i a",strtotime($p['published_date']))  . '</td>';
				echo '<td><a href="' . WEB_DIR  .'/simple-pages/editPage/' . $p['id'] . '">' . $p['title'] .  '</a></td>';
				echo '<td>' . $is_published . '</td>';
				echo '</tr>';
				
				$pageCount++;
			}
	?>
		</tbody>
	</table>
	
	<input type="hidden" name="simple_pages_page_count" value="<?php echo $pageCount; ?>" />
	
	<select name="simple_pages_selected_action" onchange="simple_pages_verify_action();" size="1">
	<option value="">---Choose Action---
	<option value="hide">Hide
	<option value="publish">Publish
	<option value="delete">Delete
	</select>
		
	</form>
	<?php
		} else {
	?>
		<p>No pages yet.</p>
	<?php 
		}
	?>
</div>


<?php foot(); ?>
