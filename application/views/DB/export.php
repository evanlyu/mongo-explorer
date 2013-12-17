<div id="content">
	<?php include 'db_menu.php'; ?>
	<h2>Export</h2>
	
	<center><h3>Choose collections</h5></center>
	
	<form method="post" action="<?php echo base_url('DB/Export/'.$_dbname); ?>">
		<table border="1" id="dbcol">
			<tr>
				<th><input type="checkbox" id="colcheckall"></th>
				<th>Collections</th>
			</tr>
			<?php foreach($dbcollection as $collvar): ?>
				<tr>
					<td align="center">
						<input type="checkbox" value="<?php echo $collvar['table_name'] ?>" class="colcheckall" name="coll[]"/>
					</td>
					<td><?php echo $collvar['table_name'] ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
		<br />
		<center>
			<button type="submit" name="meth" value='E'>Execute!</button>
		</center>
	</form>
	
	<div style="width:100%;height:500px;overflow:scroll;">
		<pre><?php echo $resultcode ; ?></pre>
	</div>
	
</div>
	<script>
		$('#colcheckall').click(function(event){
			if( $('#colcheckall').prop("checked") )
				$('.colcheckall').prop("checked",true);
			else
				$('.colcheckall').prop("checked",false);
		});
	</script>
		