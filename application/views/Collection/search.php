<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Search</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form action="<?php echo base_url('Collection/search/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>" method="get">
		<table border="1">
			<tr>
				<th align="left" width="30%">Criteria</th> 
				<td><textarea rows="8" cols="40" style="font-size: 110%" name="criteria"><?php echo $criteria; ?></textarea></td>
			</tr>
			<tr>
				<th align="left">Order</th>
				<td>
					<button type="button" id="addField">+</button>
					<br/>
					<?php 
						foreach($field as $fKey=>$fVal): 
							if($fVal=='') continue ;
					?>
						<div class="rfield">
							<input type="text" name="field[]" value="<?php echo $fVal?>">
							<select name="od[]">
								<option <?php echo ($od[$fKey]=='ASC')?'selected':'';?>>ASC</option>
								<option <?php echo ($od[$fKey]=='DESC')?'selected':'';?>>DESC</option>
							</select>
						</div>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<th align="left">Limit</th>
				<td>
					<input type="text" name="limit" value="<?php echo $limit?>">
					<input type="hidden" name="page" value="1">
				</td>
			</tr>
			<tr>
				<th align="left">Rows</th>
				<td>
					<select name="row">
						<option value="10" <?php echo ($row=='10')?'selected':'';?>>10</option>
						<option value="20" <?php echo ($row=='20')?'selected':'';?>>20</option>
						<option value="30" <?php echo ($row=='30')?'selected':'';?>>30</option>
						<option value="40" <?php echo ($row=='40')?'selected':'';?>>40</option>
						<option value="50" <?php echo ($row=='50')?'selected':'';?>>50</option>
						<option value="100" <?php echo ($row=='100')?'selected':'';?>>100</option>
					</select>
				</td>
			</tr>
		</table>
		<br>
		<center><button type="submit">Query</button></center>
	</form>
	<br>

	<?php if(count($columes)>0): ?>
		<div class="page_link">
			<?php for($i=1;$i<=$dataTotalPageCount;$i++):?>
				<a 
					href="/phpMongoEpr/Collection/search/<?php echo sprintf('%s/%s%s&page=%s',$_dbname,$_collectionname,$urParam,$i);?>"
					class="<?php echo ($nowPage==$i)?"selectedItem":"" ;?>"
				>
					<?php echo $i; ?>
				</a>
			<?php endfor ;?>
		</div>
		
		<table border="1">
			<tr>
				<th>#</th>
				<th>Data</th>
			</tr>
			<?php 
				foreach($columes as $colKey=>$colVal):
					$urObjID = @str_replace(array('ObjectId(','"',')'),'',$colVal['_id']) ; 
			?>
				<tr>
					<td><?php echo $dataTotalCount-- ; ?></td>
					<td><?php showJsonDataRecur($colVal); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>

<?php 
	function showJsonDataRecur($colData,$n=1){
		echo '{<br>';
		$endKey = key( array_slice( $colData, -1, 1, TRUE ) );
		foreach($colData as $cvKey=>$cvVal):
			echo str_repeat('&nbsp;', $n*8);
			echo '<span style="color:#E3A">"'.$cvKey.'"</span>&nbsp;:&nbsp;';
			if( !is_array($cvVal) ):
				echo '"'.addslashes($cvVal).'"' ;
			else:
				showJsonDataRecur($cvVal,++$n);
				--$n;
			endif;
			echo ($cvKey==$endKey)?'':'&nbsp;,&nbsp;';
			echo '<br>';
		endforeach;
		echo str_repeat('&nbsp;', $n*8-8);
		echo '}';
	}
?>

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

	var tabby_opts = {tabString:'	'};
	$('textarea').tabby(tabby_opts);
</script>