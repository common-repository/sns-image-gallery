<?php
global $wpdb, $obj ;
if(!empty($_POST['submit'])){
	$result = $obj->edit_logo($_POST,$_FILES['wss_image']);
}
?>
<div>&nbsp;</div>
<form method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
	<table cellpadding="0" cellspacing="0" class="block_table" width="100%">
		<tr>
			<th width="20%" ><strong>Edit Logo:</strong> <span><a href="admin.php?page=wss-logos">Back</a></span></th>
		</tr>
		<?php if($result){?>
		<tr>
			<td width="100%" style="padding:20px 20px 0px 20px;" valign="top">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr><td <?php if($result['status']=='error'){?>class="result error"<?php }else{?>class="result success"<?php }?>><?php echo $result['msg'];?></td></tr>
				</table>
			</td>
		</tr>
		<?php }$up = wp_upload_dir(); $res = $obj->get_logo($_REQUEST['id']);
		
		$imgname = preg_replace('/(.*)(\.[\w\d]{3})/', '$1-thumb$2', $res->guid);
	 
	   	$path = $uppath.'/wpslideshow/logos/'.$imgname;
		
	    $path = $_SERVER['DOCUMENT_ROOT']."/wp-content/uploads/wpslideshow/logos/".$imgname;
		
		if (file_exists($path)) {
			$curr_image = $imgname;
		} else {
			$curr_image = $res->guid;
		}
		
		?>
		<tr>
			<td width="100%" style="padding:20px;" valign="top">
				<table cellpadding="0" cellspacing="0" width="60%">
					<tr><td><h3>Current Logo</h3></td></tr>
					<tr>
						<td>
							<img src="<?php echo $up['baseurl'].'/wpslideshow/logos/'.$curr_image;?>" width="150" height="86"  />
						</td>
					</tr>
					<tr><td><h3>New Logo</h3></td></tr>
					<tr><td><input type="file" name="wss_image" id="wss_image" /></td></tr>
					<tr><td><h3>Content</h3></td></tr>
					<tr><td><?php the_editor(stripslashes($res->content),'logo_desc_content','',false,2,false); ?></td></tr>
					<tr><td><h3>Link to</h3></td></tr>
					<tr><td><input type="text" name="image_link" value="<?php echo $res->link;?>" size="60" /></td></tr>
					<tr><td><h3>Location</h3></td></tr>
					<tr><td><input type="radio" name="imglocation" id="imglocation" value="Klanten" <?php if( $res->type == "Klanten") { ?> checked="checked" <?php } ?> />&nbsp; Klanten &nbsp; <input type="radio" name="imglocation" id="imglocation" value="Homepage" <?php if( $res->type == "Homepage") { ?> checked="checked" <?php } ?>/>&nbsp; Homepage Logo &nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td><input type="submit" name="submit" value="Update" class="button-primary" /></td></tr>
				</table>
			</td>
		</tr>
	</table>
</form>