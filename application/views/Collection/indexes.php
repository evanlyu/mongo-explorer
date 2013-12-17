<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Indexes</h2>
	
	<div class="page_link">
		<a href="<?php echo base_url('Collection/addIndexes/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">+ Add Indexes</a>
	</div>
	
	<table border="1">
		<tr>
			<th>Name</th> 
			<th>Key</th>
			<th>v</th>
			<th>Unique</th>
			<th>Operation</th>
		</tr>
		<?php foreach($colIndexes as $cival): ?>
		<tr>
			<td align="left"><?php echo $cival['name'] ?></td>
			<td><pre><?php print_r($cival['key']) ?></pre></td>
			<td align="center"><?php echo $cival['v'] ?></td>
			<td align="center"><?php echo (@$cival['unique']=='1')?"Yes":"No"; ?></td>
			<td align="center">
				<?php if($cival['name']!='_id_'): ?>
				<form id="_d" method='post' action="<?php echo base_url('Collection/dropIndexes/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">
					<input type="hidden" value="<?php echo $cival['name'] ?>" name="indexname"/>
					<a href="javascript:$('#_d').submit();" onclick="return confirm('Are you sure?')">Drop</a>
				</form>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	
</div>