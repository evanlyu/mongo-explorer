<div id="content">
	<?php include 'db_menu.php'; ?>
	<h2>Duplicate</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('DB/Duplicate/'.$_dbname); ?>">
		<table border="1">
			<tr>
				<th align="left" width="30%">new Database name</th>
				<td><input type="text" id="ndbname" name="ndbname"/></td>
			</tr>
		</table>
		<br />
		<center><button type="submit" id="exc">Execute!</button></center>
	</form>
</div>
		