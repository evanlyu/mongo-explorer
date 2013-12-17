<div id="content">
	<?php include 'svr_menu.php'; ?>
	<h2>add User</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('Server/addUser')?>">
		<table border="1">
			<tr>
				<th align="left" width="30%">User Name</th> 
				<td><input type="text" name="uname"></td>
			</tr>
			<tr>
				<th align="left">Password</th>
				<td><input type="password" name="upass"></td>
			</tr>
			<tr>
				<th align="left">Comfirm Password</th>
				<td><input type="password" name="ucpass"></td>
			</tr>
			<tr>
				<th align="left">Database</th>
				<td>
					<select name="dbsel">
						<?php foreach($dblist as $dbval): ?>
							<option value="<?php echo $dbval['dbname']?>"><?php echo $dbval['dbname']?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
		<br>
		<center><button type="submit">Add or Replace</button></center>
	</form>
</div>