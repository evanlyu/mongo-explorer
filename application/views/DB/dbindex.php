<div id="content">
	<?php include 'db_menu.php'; ?>
	<h2>Statistics</h2>
	
	<table border="1">
	<?php foreach($dbinfo as $dbkey=>$dbval): ?>
		<tr>
			<td><?php echo $dbkey; ?></td>
			<td><?php print_r($dbval); ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>
		