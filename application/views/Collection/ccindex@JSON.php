<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Browser</h2>
	
	<div class="page_link">
		<?php for($i=1;$i<=$dataTotalPageCount;$i++):?>
			<a 
				href="<?php echo base_url('Collection/target/'.sprintf('%s/%s/%d',$_dbname,$_collectionname,$i)); ?>"
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
			<th>Operation</th>
		</tr>
		<?php 
			foreach($columes as $colKey=>$colVal):
				$urObjID = @str_replace(array('ObjectId(','"',')'),'',$colVal['_id']) ; 
		?>
			<tr>
				<td><?php echo $dataTotalCount ; ?></td>
				<td><?php showJsonDataRecur($colVal); ?></td>
				<td>
					<?php if($_collectionname=='system.indexes'): ?>
						<a href="<?php echo base_url('Collection/Indexes/'.str_replace('.','/',$colVal['ns'])); ?>">Manage!!</a>
					<?php elseif($_collectionname=='startup_log'): ?>
						/
					<?php elseif(isset($colVal['_id'])): ?>
						<a href="<?php echo base_url('Collection/edit/'.sprintf('%s/%s/%s',$_dbname,$_collectionname,$urObjID)); ?>">Edit!!</a><br />
						<a 
							href="<?php echo base_url('Collection/delete/'.sprintf('%s/%s/%s',$_dbname,$_collectionname,$urObjID)); ?>"
							onClick="return confirm('Are you sure to delete the row #<?php echo $dataTotalCount--; ?>?')"
						>Delete!!</a>
					<?php else: ?>
						/
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
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
		