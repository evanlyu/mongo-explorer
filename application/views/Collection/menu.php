<div class="subfunc">
	<a href="<?php echo base_url('Collection/target/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>" class="dbahref">
		`<?php echo $_dbname ?>`.`<?php echo $_collectionname ?>`
	</a>
	<!-- <a href="#">RowType:
		<select id="showType">
			<option selected >JSON</option>
			<option <?php if($_showType=='Grid') echo 'selected' ?>>Grid</option>
		</select>
	</a>
	 -->
	<a href="<?php echo base_url('Collection/Search/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">Search</a>
	<a href="<?php echo base_url('Collection/Insert/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">Insert</a>
	<a href="<?php echo base_url('Collection/newField/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">new Field</a>
	<a href="<?php echo base_url('Collection/Indexes/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">Indexes</a>
	<a href="<?php echo base_url('Collection/Clear/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">Clear</a>
	<a href="<?php echo base_url('Collection/Drop/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">Drop</a>
</div>
<div class="subfunc" style="text-align: right;">
	<a href="<?php echo base_url('Collection/Duplicate/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">Duplicate</a>
	<a href="<?php echo base_url('Collection/Rename/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">Rename</a>
	<a href="<?php echo base_url('Collection/Statistics/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">Statistics</a>
	<a href="<?php echo base_url('Collection/Validate/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">Validate</a>
</div>

<script type="text/javascript">
	$('#showType').change(function(){
		$.post(
			'/phpMongoEpr/Collection/changeType',
			{'showtype':$('#showType').val()},
			function(info){
				location.reload();
			}
		)
	});
</script>