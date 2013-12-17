<div id="content">
	<?php include 'svr_menu.php'; ?>
	<h2>Process (db.$cmd.sys.inprog.find({$all:1}))</h2>
	
	<?php foreach($proginfo as $infKey=>$infVal): ?>
		<table border="1">
			<tr>
				<th colspan="2"><?php echo $infVal['opid']; ?></th>
			</tr>
			<?php unset($infVal['opid']); ?>
			<?php foreach($infVal as $infDatKey=>$infDatVal): ?>
				<tr>
					<td><?php echo $infDatKey;?></td>
					<td><?php print_r($infDatVal);?></td>
				</tr>
			<?php endforeach; ?>
		</table>
		<br>
	<?php endforeach; ?>
	<div style="clear:both">&nbsp;</div>
</div>
		