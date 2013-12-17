<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Statistics</h2>
	
	<table border="1">
		<tr>
			<th colspan="2">stats()</th>
		</tr>
		<?php foreach($statInfo as $infKey=>$infVal): ?>
			<tr>
				<td><?php echo $infKey; ?></td>
				<td><?php print_r($infVal); ?></td>
			</tr>
		<?php endforeach;; ?>
	</table>
	<br />
	<table border="1">
		<tr>
			<th colspan="2">{top:1}</th>
		</tr>
		<?php foreach($topInfo as $topKey=>$topVal): ?>
			<tr>
				<td><?php echo $topKey; ?></td>
				<td><?php print_r($topVal); ?></td>
			</tr>
		<?php endforeach;; ?>
	</table>
	
</div>
		