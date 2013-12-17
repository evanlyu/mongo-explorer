<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Validate</h2>
	
	<table border="1">
		<tr>
			<th colspan="2">Validate Info</th>
		</tr>
		<?php foreach($validInfo as $infKey=>$infVal): ?>
			<tr>
				<td><?php echo $infKey; ?></td>
				<td><?php print_r($infVal); ?></td>
			</tr>
		<?php endforeach;; ?>
	</table>
	
</div>
		