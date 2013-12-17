<div id="content">
	<?php include 'db_menu.php'; ?>
	<h2>Profile</h2>
	
	<div class="page_link">
		<a href="<?php echo base_url('DB/changeProfileLevel/'.$_dbname); ?>">Change level</a>
		<a href="<?php echo base_url('DB/clearProfile/'.$_dbname); ?>" onClick="return confirm('Are you sure?')">Clear!!</a>
	</div>
	
	<?php foreach ($gProfile as $pKey=>$pVal): ?>
		<table border="1">
			<tr>
				<th colspan="2"><?php echo $pVal['ts']; unset($pVal['ts']); ?></th>
			</tr>
			<?php foreach ($pVal as $p2Key=>$p2Val): ?>
			<tr>
				<td><?php echo $p2Key; ?></td>
				<td><?php print_r($p2Val); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<br>
	<?php endforeach; ?>
	
</div>