<?php
global $wpdb, $obj ;
if(!empty($_POST['submit'])){
	$result = $obj->add_logo($_POST,$_FILES['wss_image']);
}
?>
<div>&nbsp;</div>
<form method="post" action="" enctype="multipart/form-data">
	<table cellpadding="0" cellspacing="0" class="block_table" width="100%">
		<tr>
			<th width="20%" ><strong>Add New Logo:</strong> </th>
		</tr>
		<?php if($result){?>
		<tr>
			<td width="100%" style="padding:20px 20px 0px 20px;" valign="top">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr><td <?php if($result['status']=='error'){?>class="result error"<?php }else{?>class="result success"<?php }?>><?php echo $result['msg'];?></td></tr>
				</table>
			</td>
		</tr>
		<?php }?>
		<tr>
			<td width="100%" style="padding:20px;" valign="top">
				<table cellpadding="0" cellspacing="0" width="60%">
					<tr><td><h3>Upload Logo</h3></td></tr>
					<tr><td><input type="file" name="wss_image" id="wss_image" /></td></tr>
					<tr><td><h3>Content</h3></td></tr>
					<tr><td><?php the_editor(stripslashes($content),'logo_desc_content','',false,2,false); ?></td></tr>
					<tr><td><h3>Link to</h3></td></tr>
					<tr><td><input type="text" name="image_link" value="" size="60" /></td></tr>
					<tr><td><h3>Alter Text</h3></td></tr>
					<tr><td><input type="text" name="alter_text" value="" size="60" /></td></tr>
					<tr><td><h3>Location</h3></td></tr>
					<tr><td><input type="radio" name="imglocation" id="imglocation" value="Klanten" <?php if($_POST['imglocation']== "Klanten") { ?>checked="checked" <?php } ?> />&nbsp; Klanten &nbsp; <input type="radio" name="imglocation" id="imglocation" value="Homepage" <?php if($_POST['imglocation']== "Homepage"){ ?> checked="checked" <?php } ?>/>&nbsp; Homepage Logo &nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td><input type="submit" name="submit" value="Submit" class="button-primary" /></td></tr>
				</table>
			</td>
		</tr>
	</table>
</form>