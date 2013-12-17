<div class="subfunc">
	<a href="<?php echo base_url('DB/target/'.$_dbname) ;?>" class="dbahref">`<?php echo $_dbname ?>`</a> <!-- Statictis -->
	<a href="<?php echo base_url('DB/newCollection/'.$_dbname) ;?>">new Collection</a>
	<!-- <a href="/phpMongoEpr/DB/Duplicate/<?php echo $_dbname ?>">Duplicate</a> -->
	<a href="<?php echo base_url('DB/Export/'.$_dbname) ;?>">Export</a>
	<a href="<?php echo base_url('DB/Import/'.$_dbname) ;?>">Import</a>
	<a href="<?php echo base_url('DB/Profile/'.$_dbname) ;?>">Profile</a>
	<a href="<?php echo base_url('DB/Repair/'.$_dbname) ;?>">Repair</a>
	<a href="<?php echo base_url('DB/Clear/'.$_dbname) ;?>">Clear</a>
	<a href="<?php echo base_url('DB/Drop/'.$_dbname) ;?>">Drop</a>
</div>