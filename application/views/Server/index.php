<div id="content">
	<?php include 'svr_menu.php'; ?>
	<h2>Server</h2>
	<?php foreach($sysinfo as $info): ?>
	<table border=1>
		<tr>
			<th colspan="2"><?php echo $info['name'] ?></th>
		</tr>
			<?php foreach($info['value'] as $infokey=>$infoval): ?>
				<tr>
					<td><?php echo $infokey; ?></td>
					<td>
						<?php print_r($infoval); ?></td>
					</td>
				</tr>
			<?php endforeach; ?>
	</table>
	<br />
	<?php endforeach; ?>

	<table border=1>
		<tr>
			<th colspan="4">Directives</th>
		</tr>
		<tr>
			<th>name</th>
			<th>global_value</th>
			<th>local_value</th>
			<th>access</th>
		</tr>
		<?php foreach($directives as $diKey=>$diVal): ?>
			<tr>
				<td><?php echo $diKey; ?></td>
				<td><?php echo $diVal['global_value']; ?></td>
				<td><?php echo $diVal['local_value']; ?></td>
				<td><?php echo $diVal['access']; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
		