<div id="content">
	<?php include 'menu.php'; ?>
	<h2>new Field</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('Collection/newfield/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">
		<table border="1">
			<tr>
				<th align="left" width="30%">Field name</th>
				<td><input type="text" name="fieldname"/></td>
			</tr>
			<tr>
				<th align="left">Keep exists</th>
				<td><input type="checkbox" value="1" name="keepexists" checked/></td>
			</tr>
			<tr>
				<th align="left">Data Type</th>
				<td>
					<select name="datatype">
						<option value="string" selected="selected">String</option>
						<option value="integer">Integer</option>
						<option value="long">Long</option>
						<option value="double">Double</option>
						<option value="boolean">Boolean</option>
						<option value="null">NULL</option>
						<option value="mixed">Mixed</option>
					</select>
				</td>
			</tr>
			<tr>
				<th align="left">Value</th>
				<td>
					<textarea rows="10" cols="50" name="val"></textarea>
				</td>
			</tr>
		</table>
		<br />
		<center><button type="submit" id="exc">Apply to All!!</button></center>
	</form>
</div>

<script type="text/javascript">
	var tabby_opts = {tabString:'	'};
	$('textarea').tabby(tabby_opts);
</script>
		