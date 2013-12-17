<div id="content">
	<?php include 'db_menu.php'; ?>
	<h2>new Collection</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('DB/newCollection/'.$_dbname); ?>">
		<table border="1">
			<tr>
				<th align="left" width="30%">new Collection name</th>
				<td><input type="text" id="collectionname" name="collectionname"/></td>
			</tr>
			<tr>
				<th align="left">Is Capped</th>
				<td><input type="checkbox" value="1" id="capped" name="capped"/></td>
			</tr>
			<tr>
				<th align="left">Size</th>
				<td><input type="text" value="0" id="size" name="size"/></td>
			</tr>
			<tr>
				<th align="left">Max</th>
				<td><input type="text" value="0" id="max" name="max"/></td>
			</tr>
		</table>
		<br />
		<center><button type="submit" id="exc">Execute!</button></center>
	</form>
</div>
		