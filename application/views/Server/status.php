<div id="content">
	<?php include 'svr_menu.php'; ?>
	<h2>Status</h2>
	
	<table border=1>
		<tr>
			<th colspan="2">Server Status ({serverStatus:1})</th>
		</tr>
		<?php foreach($statusinfo as $infoKey=>$infoVal): ?>
			<tr>
				<td><?php echo $infoVal['name']; ?></td>
				<td><pre><?php print_r($infoVal['value']); ?></pre>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
		