<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Insert</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('Collection/insert/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">
		<table border="1">
			<tr>
				<th align="left">Data (JSON)</th>
			</tr>
			<tr>
				<td align="center">
					<textarea rows="20" cols="50" style="font-size:110%;" name="data"><?php if($data!=''): echo ($data); else: ?>
{

}<?php endif; ?></textarea>
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