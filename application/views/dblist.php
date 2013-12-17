	<div id="main">
		<div id="dblist">
			<ul>
				<li><a href="<?php echo base_url(); ?>">Server Status</a></li>
				<li><a href="<?php echo base_url('Server/newdb'); ?>">+ new Database</a></li>
			</ul>
			<?php foreach($dblist as $db): ?>
			<ul>
				<li>
					<a 
						href="<?php echo base_url('DB/target/'.$db['dbname']); ?>"
						class="<?php echo ($_dbname==$db['dbname']&&$_nController=='DB')?'selectedItem':''; ?>"
					>
					<?php echo $db['dbname'] ;?>
					(<?php echo $db['sizeOnDisk']?>MB)</a>
				</li>
				<ul class="dbtable">
					<?php foreach($this->mongo_db->list_db_collection($db['dbname']) as $table): ?>
					<li class="selec/tedItem">
						<a 
							href="<?php echo base_url('Collection/target/'.$db['dbname'].'/'.$table['table_name']); ?>"
							class="<?php echo ($_dbname==$db['dbname']&&$_collectionname==$table['table_name']&&$_nController=='Collection')?'selectedItem':''; ?>"
						>
							<?php echo $table['table_name'] ;?>
							(<?php echo $table['table_count'] ;?>)
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</ul>
			<?php endforeach; ?>
		</div>