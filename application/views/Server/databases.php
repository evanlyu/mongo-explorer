<div id="content">
	<?php include 'svr_menu.php'; ?>
	<h2>Databases</h2>
	
	<?php foreach($dbinfo as $infKey=>$infVal): ?>
	<div style="width:50%;float:left;height:420px;">
		<table border="1">
			<tr>
				<th colspan="2"><?php echo $infVal['db']; ?></th>
			</tr>
			<?php unset($infVal['db']); ?>
			<?php foreach($infVal as $infDatKey=>$infDatVal): ?>
				<tr>
					<td><?php echo $infDatKey;?></td>
					<td><?php print_r($infDatVal);?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<?php endforeach; ?>
	<div style="clear:both">&nbsp;</div>
</div>
		