<div id="content">
	<?php include 'svr_menu.php'; ?>
	<h2>Users</h2>
	
	<div class="page_link">
		<a href="<?php echo base_url('Server/addUser')?>">+ Add User</a>
	</div>
	
	<table border="1">
		<tr>
			<th>ID</th> 
			<th>User</th>
			<th>Roles</th>
			<th>Operation</th>
		</tr>
		<?php foreach($userauth as $uaval): ?>
		<tr>
			<td align="left"><?php echo $uaval['_id'] ?></td>
			<td><?php echo $uaval['user'] ?></td>
			<td><pre><?php print_r($uaval) ?></pre></td>
			<td>
				<a href="<?php echo base_url('Server/delUser/'.$uaval['user']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	
</div>