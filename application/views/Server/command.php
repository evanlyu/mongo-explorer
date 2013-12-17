<div id="content">
	<?php include 'svr_menu.php'; ?>
	<h2>Command</h2>
	
	<table border="1">
		<tr>
			<th>Command Input 
				<a href="http://docs.mongodb.org/manual/reference/command/" target="_new">(Database Commands)</a></th>
			<th>Databases</th>
			<th>Execute!</th>
		</tr>
		<tr>
			<td align="center">
				<textarea id="cmdInput" rows=3 cols=40 style="font-size: 110%;">[{"listCommands":"1"}]</textarea>
			</td>
			<td align="center">
				<select id="db">
					<?php foreach($dblist as $dbval): ?>
					<option><?php echo $dbval['dbname']; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td align="center"><button id="exc">Execute!</button></td>
		</tr>
	</table>
	
	<div id="responseFS"></div>
	
</div>

<script type="text/javascript">
	$( "#exc" ).click(function() {
		var dbs = $("#db").val();
		var cmd = $("#cmdInput").val();
		$.post( "<?php echo base_url('Server/doCommand')?>", { dbs:dbs,cmd:cmd }, function( data ) {
			$("#responseFS").html('<h4>Response from server:</h4><pre>'+data+'</pre>');
		});
	});
</script>
		