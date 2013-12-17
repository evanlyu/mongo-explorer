<div id="content">
	<?php include 'db_menu.php'; ?>
	<h2>Choose current profiling level</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('DB/changeProfileLevel/'.$_dbname); ?>">
		<table border="1">
			<tr>
				<th align="left" width="30%">select</th>
				<td>
				<select name="level">
					<option value="0" selected="selected">0 - off: </option>
					<option value="1" <?php echo ($nowLevel==1)?"selected":""; ?>>1 - log slow operations (&gt;N ms)</option>
					<option value="2" <?php echo ($nowLevel==2)?"selected":""; ?>>2 - log all operations</option>
				</select>
				</td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="slowms" size="7" value="50">
		<center><button type="submit" id="exc">Save!</button></center>
	</form>
</div>
		