<div id="content">
	<?php include 'menu.php'; ?>
	<h2>add Indexes</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('Collection/addIndexes/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">
		<table border="1">
			<tr>
				<th align="left" width="30%">Name</th> 
				<td><input type="text" name="name"></td>
			</tr>
			<tr>
				<th align="left">Fields</th>
				<td>
					<div class="rfield">
						<input type="text" name="field[]">
						<select name="od[]">
							<option>ASC</option>
							<option>DESC</option>
						</select>
						<button type="button" id="addField">+</button>
					</div>
				</td>
			</tr>
			<tr>
				<th align="left">Unique (Remove duplicates)</th>
				<td><input type="checkbox" name="unique"></td>
			</tr>
		</table>
		<br>
		<center><button type="submit">Create</button></center>
	</form>
</div>

<script type="text/javascript">
	$('#addField').click(function(event){
		var newFieldNode = $(document.createElement('div'))
	     .attr("class","rfieldapp");

		newFieldNode.after().html('<input type="text" name="field[]" id="textbox" value="" >'+
		'<select name="od[]">'+
			'<option>ASC</option>'+
			'<option>DESC</option>'+
		'</select>'
		);

		newFieldNode.appendTo(".rfield");
	});
</script>