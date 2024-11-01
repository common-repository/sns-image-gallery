<?php
global $wpdb, $obj ;
if(!empty($_POST['submit'])){
	$result = $obj->edit_image($_POST,$_FILES['wss_image']);
}
?>
<div>&nbsp;</div>
<form method="post" action="" enctype="multipart/form-data" onsubmit="return validate()" name="edit_images">
	<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
	<table cellpadding="0" cellspacing="0" class="block_table" width="100%">
		<tr>
			<th width="20%" ><strong>Edit Image:</strong> <span><a href="admin.php?page=wss-images">Back</a></span></th>
		</tr>
		<?php if($result){?>
		<tr>
			<td width="100%" style="padding:20px 20px 0px 20px;" valign="top">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr><td <?php if($result['status']=='error'){?>class="result error"<?php }else{?>class="result success"<?php }?>><?php echo $result['msg'];?></td></tr>
				</table>
			</td>
		</tr>
		<?php }$up = wp_upload_dir(); $res = $obj->get_image($_REQUEST['id']);?>
		<tr>
			<td width="100%" style="padding:20px;" valign="top">
				<table cellpadding="0" cellspacing="0" width="60%">
					<tr><td><h3>Current Image</h3></td></tr>
					<tr>
						<td>
							<img src="<?php echo $up['baseurl'].'/wp_slideshow/'.preg_replace('/(.*)(\.[\w\d]{3})/', '$1$2', $res->guid);?>" width="150" height="150"  />
						</td>
					</tr>
					<tr><td><h3>New Image</h3></td></tr>
					<tr><td><input type="file" name="wss_image" id="wss_image" /></td></tr>
					<!--<tr><td><h3>Content</h3></td></tr>
					<tr><td><?php //the_editor(stripslashes($res->content),'desc_content','',false,2,false); ?></td></tr>
					<tr><td>&nbsp;</td></tr>-->
					<tr><td><h3>Image Name</h3></td></tr>
					<tr><td><input type="text" name="image_link" value="<?php echo $res->name;?>" size="60" /></td></tr>
					<tr><td><h3>Alternative Text</h3></td></tr>
					<tr><td><input type="text" name="alter_text" value="<?php echo $res->link;?>" size="60" /></td></tr>
					<tr><td><h3>Description</h3></td></tr>
					<tr><td><input type="text" name="desc_content" value="<?php echo $res->content;?>" size="60" /></td></tr>
					<tr><td><h3>Pages</h3></td></tr>
					<tr>
						<td>
							<ul id="page_list">
								<?php 
									$plists = explode(',',$res->pages);
									$pagelists = get_posts( 'post_type=page&numberposts=-1&orderby=title&order=ASC' ); 
									foreach($pagelists as $pagelist){
										(in_array($pagelist->ID, $plists))? $che = 'checked="checked"' : $che = '';
										($res->default == 1)? $che1 = 'checked="checked"' : $che1 = '';
								?>
										<li>
											<input type="radio" name="pagelist[]" value="<?php echo $pagelist->ID; ?>" <?php echo $che;?> />
											<label for="pagelist"><?php echo $pagelist->post_title;?></label>
										</li>
								<?php }?>
							</ul>
						</td>
					</tr>
					<!--<tr><td><h3>Add in default set&nbsp;&nbsp;<input type="checkbox" name="default_set" value="1" <?php echo $che1;?> /></h3></td></tr>-->
					<tr><td><input type="submit" name="submit" value="Update" class="button-primary" /></td></tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<script>
function validate(){
	if( document.edit_images.image_link.value == "" ){
		alert("Enter the Image Name Text");
		edit_images.image_link.focus();
		return false;
	}
	if( document.edit_images.alter_text.value == "" ){
		alert("Enter the Alternative Text");
		edit_images.alter_text.focus();
		return false;
	}
	if( document.edit_images.desc_content.value == "" ){
		alert("Enter the description");
		edit_images.desc_content.focus();
		return false;
	}
	var i, chks = document.getElementsByName('pagelist[]');
    for (i = 0; i < chks.length; i++)
        if (chks[i].checked)
            return true;
    alert('please selet the page');
    return false;
}
</script>