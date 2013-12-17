<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Edit/Update</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('Collection/edit/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">
		<table border="1">
			<tr>
				<th align="left">_id</th>
				<td><?php echo $MongoID ;?><input type="hidden" name="_id" value="<?php echo $MongoID?>"></td>
			</tr>
			<tr>
				<th>Data (JSON)</th>
				<td align="center">
					<textarea rows="20" cols="50" style="font-size:110%;" name="_data"><?php showJsonDataRecur($resData)?></textarea>
				</td>
			</tr>
		</table>
		<br />
		<center><button type="submit" id="exc">Save!</button></center>
	</form>
</div>
		
<script type="text/javascript">
	var tabby_opts = {tabString:'	'};
	$('textarea').tabby(tabby_opts);
</script>

<?php 
	function showJsonDataRecur($colData,$n=1){
		echo '{'.chr(13).chr(10);
		$endKey = key( array_slice( $colData, -1, 1, TRUE ) );
		foreach($colData as $cvKey=>$cvVal):
			echo str_repeat(' ', $n*8);
			echo '"'.$cvKey.'":';
			if( !is_array($cvVal) ):
				echo '"'.addslashes($cvVal).'"' ;
			else:
				showJsonDataRecur($cvVal,++$n);
				--$n;
			endif;
			echo ($cvKey==$endKey)?'':',';
			echo chr(13).chr(10);
		endforeach;
		echo str_repeat(' ', $n*8-8);
		echo '}';
	}
?>