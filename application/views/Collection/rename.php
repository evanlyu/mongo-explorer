<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Rename</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('Collection/rename/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">
		<table border="1">
			<tr>
				<th align="left" width="30%">Old Name</th>
				<td><input type="text" value="<?php echo $_collectionname?>" name="cololdname" readonly/></td>
			</tr>
			<tr>
				<th align="left">New Name</th>
				<td><input type="text" value=""  name="colnewname"/></td>
			</tr>
			<tr>
				<th align="left">Drop if exists?</th>
				<td><input type="checkbox" value="1" name="dropexist"/></td>
			</tr>
		</table>
		<br />
		<center><button type="submit" id="exc">Execute!</button></center>
	</form>
</div>
		