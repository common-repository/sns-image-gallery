<?php
global $wpdb, $obj ;
if($_REQUEST['del_id']){$del_res = $obj->delete_logo($_REQUEST['del_id']);}
?>
<div>&nbsp;</div>
<form method="post" action="" enctype="multipart/form-data">
	<table cellpadding="10" cellspacing="0" class="images_table" width="100%">
		
		<tr>
			<th width="3%">ID</th>
			<th width="20%">Image</th>
			<th width="37%">Link</th>
			<th width="17%">Location</th>
			<th width="15%">Actions</th>
		</tr>
		<?php if($del_res == 1){?>
		<tr><td class="result success last" colspan="5" align="center">One Row Deleted Successfully.</td></tr>
		<?php }$up = wp_upload_dir(); $images = $obj->get_logo();if($images){ foreach($images as $key => $img){
		
		$imgname = preg_replace('/(.*)(\.[\w\d]{3})/', '$1-thumb$2', $img->guid);
	 
	   	$path = $uppath.'/wpslideshow/logos/'.$imgname;
		
	    $path = $_SERVER['DOCUMENT_ROOT']."/wp-content/uploads/wpslideshow/logos/".$imgname;
		
		if (file_exists($path)) {
			$curr_image = $imgname;
		} else {
			$curr_image = $img->guid;
		}
		
		?>
		<tr <?php if($key%2 != 0){?>class="odd"<?php }?>>
			<td valign="top" align="center"><?php echo $img->id;?></td>
			<td valign="top" align="center">
				<img src="<?php echo $up['baseurl'].'/wpslideshow/logos/'.$curr_image; ?>" height="50"  />
			</td>
			<td><?php echo $img->link;?></td>
			<td style="text-align:center"><?php echo $img->type;?></td>
			<td align="center" class="last">
				<a href="admin.php?page=wss-edit-logo&id=<?php echo $img->id;?>">Edit</a> | 
				<a href="admin.php?page=wss-logos&del_id=<?php echo $img->id;?>">Delete</a>
			</td>
		</tr>
		
		<?php }}else{?>
		<tr><td class="last" colspan="5" align="center">- No image - <a href="admin.php?page=wss-add-logo">Add New</a></td></tr>
		<?php }?>
		
		
				
	</table>
</form>