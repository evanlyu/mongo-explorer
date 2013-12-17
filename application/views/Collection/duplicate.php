<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Duplicate</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('Collection/Duplicate/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">
		<table border="1">
			<tr>
				<th align="left" width="30%">From</th>
				<td><input type="text" value="<?php echo $_collectionname?>" name="colfrom" readonly/></td>
			</tr>
			<tr>
				<th align="left">To</th>
				<td><input type="text" value="<?php echo $_collectionname?>_copy" name="colto"/></td>
			</tr>
			<tr>
				<th align="left">Remove target if exists?</th>
				<td><input type="checkbox" value="1" name="droptarget" checked/></td>
			</tr>
			<tr>
				<th align="left">Copy Indexes?</th>
				<td><input type="checkbox" value="1" name="copyindex" checked/></td>
			</tr>
		</table>
		<br />
		<center><button type="submit" id="exc">Execute!</button></center>
	</form>
</div>
		