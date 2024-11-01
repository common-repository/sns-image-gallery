<?php
global $wpdb, $obj ;
if($_REQUEST['del_id']){$del_res = $obj->delete_image($_REQUEST['del_id']);}
$title = $obj->postTile($_POST['title_name']);
?>
<div>&nbsp;</div>
   <div class="searchformfilter">
   <?php 
	 global $wpdb;
	$prefix = $wpdb->prefix;
   ?>
	<form action="" method="post" name="searchformfilter" onsubmit="return validate()">
		<!--<label>Enter the Alt Text</label>
		<input type="text" name="searchterm" id="searchterm"  value="<?php// if(isset($_POST['searchterm'])){echo $_POST['searchterm'];}?>" autocomplete="off"/>-->
		<label>Select the Pages</label>
		<select name="title_name">
		<option value="">Please select</option>
		<?php $pagelists = get_posts( 'post_type=page&numberposts=-1&orderby=title&order=DESC' ); foreach($pagelists as $pagelist){?>
		<?php $page_id = $obj->postId($pagelist->ID);
			$select_user = "select pages from ".$prefix."wss_images where pages = '".$pagelist->ID."'";
			$queryspot = $wpdb->get_results($select_user);
			if(count($queryspot) >= 1){ ?>
			<?php $postcontent = $pagelist->post_content;
			?>		
		<option value="<?php echo $pagelist->ID; ?>" <?php if ($_POST['title_name'] == $pagelist->ID) { ?> selected="selected"<?php } ?>><?php echo $pagelist->post_title;?></option>
		<?php }}?>
		</select>
			<input type="submit" value="search" name="submit" class="slideshow_search"/>
	</form>
	</div>
<form method="post" action="" enctype="multipart/form-data">
	<table cellpadding="10" cellspacing="0" class="images_table" width="100%">
		
		<tr>
			<th width="3%">S.no</th>
			<th width="10%">Image</th>
			<th width="20%">Image Name</th>
			<th width="27%">Pages</th>
			<th width="20%">Alt Text</th>
			<th width="27%">Description</th>
			<th width="10%">Actions</th>
		</tr>
		<?php if($del_res == 1){?>
		<tr><td class="result success last" colspan="5" align="center">One Row Deleted Successfully.</td></tr>
		<?php }
		$up = wp_upload_dir();
		$images = $obj->get_image();
		if($images){ 
		foreach($images as $key => $img){
		?>
		<tr <?php if($key%2 != 0){?>class="odd"<?php }?>>
			<td valign="center" align="center"><?php /*echo $img->id;*/echo $key + 1; ?></td>
			<td valign="center" align="center">
            <?php 	if(file_exists($up['basedir'].'/wp_slideshow/'.preg_replace('/(.*)(\.[\w\d]{3})/', '$1-thumb$2', $img->guid))){ ?>
				<img src="<?php echo $up['baseurl'].'/wp_slideshow/'.preg_replace('/(.*)(\.[\w\d]{3})/', '$1-thumb$2', $img->guid);?>" width="50" height="50"  />
			<?php	}else { ?>
				<img src="<?php echo $up['baseurl'].'/wp_slideshow/'.preg_replace('/(.*)(\.[\w\d]{3})/', '$1$2', $img->guid);?>" width="50" height="50"  />
						<?php } ?>
			</td>
			<!--<td><?php //if($img->content){echo substr(strip_tags($img->content),0,200).'...';}?></td>-->
			<td><?php echo $img->name;?></td>
			<td><?php
			$split   =  explode(",",$img->pages);
			for($i=0;$i<count($split);$i++){
			 /*$result = mysql_query("select post_title from wp_posts where id = '".$split[$i]."'");
			 while($rows = mysql_fetch_assoc($result)){
			 	print_r ($rows['post_title'].", ");
			 }*/
			 $result = $obj->postTile($split[$i]);
			 echo $result;
			 }
			  ?></td>
			<td><?php echo $img->link;?></td>
			<td><?php echo $img->content;?></td>
			<td align="center" class="last">
				<a href="admin.php?page=wss-edit-image&id=<?php echo $img->id;?>">Edit</a> | 
				<a href="admin.php?page=wss-images&del_id=<?php echo $img->id;?>" onclick="return confirmDelete()">Delete</a>
			</td>
		</tr>
		<?php }}else{?>
		<tr><td class="last" colspan="5" align="center">- No image - <a href="admin.php?page=wss-add-image">Add New</a></td></tr>
		<?php }?>
				
	</table>
</form>

<?php 
	/*$rs_result = mysql_query("SELECT COUNT(*) FROM wp_wss_images");
	$row = mysql_fetch_row($rs_result);
	$total_records = $row[0];
	$total_pages = ceil($total_records / 25);
	
	echo "<div class='pagination'>";  
	  if ($total_records > 25) {
	for ($i=1; $i<=$total_pages; $i++) {
				echo "<a href='http://snsdev3/jwhitephoto/wp-admin/admin.php?page=wss-images&index=".$i."'>".$i."</a>";
	};
	echo "</div>";
	}*/


?>
<script>
function validate(){
	if( document.searchformfilter.title_name.value == "" ){
		alert("please select the page");
		return false;
	}
}
function confirmDelete(){
	if(confirm("Are you sure. Do you want to delete this!")){
		return true;	
	}else{
		return false;		
	}
}
</script>