<?php
class wpslideshow{
	function get_image($id = null){ 
		global $wpdb;
		$prefix = $wpdb->prefix;
		/*if (isset($_POST['searchterm'])){
				$whereCondition = " where alter_text LIKE '%".$_POST['searchterm']."%'" ;
			}
		if (isset($_GET["index"]))
		{ 
			$index  = $_GET["index"];
		 }
		else
		{
			$index=1;
		}	
		$start_from = ($index-1) * 25;*/
		$whereCondition	=	"";
		$limit	=	" Limit 0, 15";
		if ($_POST['title_name'] != ''){
			$whereCondition .= " and pages LIKE '%".$_POST['title_name']."%'";
			$limit	=	" ";
		}
		
		if ($_POST['searchterm'] != ''){
			$whereCondition .= "  and alter_text LIKE '%".$_POST['searchterm']."%'  ";
			$limit	=	" ";
		}
		
		if(!$id){
		$res = $wpdb->get_results('SELECT * FROM '.$prefix.'wss_images WHERE id  !="" '.$whereCondition.' ORDER BY id DESC '.$limit);
	//res = $wpdb->get_results('SELECT * FROM '.$prefix.'wss_images'.$whereCondition.' ORDER BY id DESC LIMIT '.$start_from.', 25');
			//$res = $wpdb->get_results('SELECT * FROM '.$prefix.'wss_images LEFT JOIN wp_posts ON wp_posts.id = wp_wss_images.id ORDER BY wp_wss_images.id DESC');
			//print 'SELECT * FROM '.$prefix.'wss_images'.$whereCondition.' ORDER BY id DESC'; 
		}
		else{
			$res = $wpdb->get_row('SELECT * FROM '.$prefix.'wss_images WHERE id = '.$id);
		}
		return $res;
	}
	function get_logo($id = null){ 
		global $wpdb;
		$prefix = $wpdb->prefix;
		if(!$id){
			$res = $wpdb->get_results('SELECT id, link, guid,content type FROM '.$prefix.'wss_logos ORDER BY id ASC');
		}
		else{
			$res = $wpdb->get_row('SELECT * FROM '.$prefix.'wss_logos WHERE id = '.$id);
		}
		return $res;
	}

	function add_image($postArray, $fileArray){
		global $wpdb;
		$prefix = $wpdb->prefix;
		//$name = $_FILES['wss_image']['name'];
		$content = $postArray['desc_content'];
		$name = $postArray['image_link'];
		$pages = ($postArray['pagelist'])?implode(',', $postArray['pagelist']):'';
		$default = ($postArray['default_set'])?1:0;
		$alter_text = $postArray['alter_text'];
		if($fileArray['error']) { 
			$res['status'] = 'error'; $res['msg'] = 'Upload a image'; 
		}
		else{
			$explode_page = explode(",",$pages);
				for($i=0;$i<count($explode_page);$i++){
				//$wpdb->insert($prefix.'wss_images', array( 'content' => $content, 'link' => $link, 'pages' => $explode_page[$i], 'default' => $default ));
				$wpdb->insert($prefix.'wss_images', array( 'name' => $name, 'content' => $content, 'link' => $alter_text, 'pages' => $explode_page[$i], 'default' => $default ));  
				if(($fileArray["type"] == 'image/jpeg') || ($fileArray["type"] == 'image/pjpeg'))	{
					$suf = ".jpg";
				}
				elseif($fileArray["type"] == 'image/gif')	{
					$suf = ".gif";
				}
				elseif($fileArray["type"] == 'image/png')	{
					$suf = ".png";
				}
				$img_insert_id	=	$wpdb->insert_id;
				$filename = WSS_UPLOADS_DIR.'/'.$img_insert_id.$suf;
				
				/*move_uploaded_file($fileArray['tmp_name'], $filename.'/large');*/
				move_uploaded_file($fileArray['tmp_name'], $filename);
				image_resize($filename,500,auto,false,'thumb',WSS_UPLOADS_DIR);
				
				//$wpdb->update($prefix.'wss_images',array( 'guid' => $wpdb->insert_id.$suf ), array( 'id' => $wpdb->insert_id ) );
				$wpdb->query("UPDATE ".$prefix."wss_images"." SET guid = '".$img_insert_id.$suf."' WHERE id = '".$img_insert_id."'");
			}
			
			$res['status'] = 'success'; $res['msg'] = 'Image added successfully';
		
		}
		
		return $res;
	}
	
	function add_logo($postArray, $fileArray){
		global $wpdb;
		$prefix = $wpdb->prefix;
		
		$link = $postArray['image_link'];
		$type = $postArray['imglocation'];
		$logo_desc_content = $postArray['logo_desc_content'];
		$alter_text = $postArray['alter_text'];
		
		
		if($fileArray['error']) { 
			$res['status'] = 'error'; $res['msg'] = 'Upload a image'; 
		}
		else{
			$wpdb->insert($prefix.'wss_logos', array( 'link' => $link, 'type' => $type , 'content' => $logo_desc_content, 'alter_text' => $alter_text));
			
			if(($fileArray["type"] == 'image/jpeg') || ($fileArray["type"] == 'image/pjpeg'))	{
				$suf = ".jpg";
			}
			elseif($fileArray["type"] == 'image/gif')	{
				$suf = ".gif";
			}
			elseif($fileArray["type"] == 'image/png')	{
				$suf = ".png";
			}
			
			$filename = WSS_UPLOADS_DIR.'/logos/'.$wpdb->insert_id.$suf;
			move_uploaded_file($fileArray['tmp_name'], $filename);
			image_resize($filename,150,86,false,'thumb',WSS_UPLOADS_DIR.'/logos');
			
			$wpdb->update($prefix.'wss_logos',array( 'guid' => $wpdb->insert_id.$suf ), array( 'id' => $wpdb->insert_id ) );
			
			$res['status'] = 'success'; $res['msg'] = 'Image added successfully';
		}
		
		return $res;
	}
	
	function edit_image($postArray, $fileArray){
		global $wpdb;
		$prefix = $wpdb->prefix;
		//$name = $_FILES['wss_image']['name'];
		$id = $postArray['id'];
		$content = $postArray['desc_content'];
		$name = $postArray['image_link'];
		$pages = ($postArray['pagelist'])?implode(',', $postArray['pagelist']):'';
		$alter_text = $postArray['alter_text'];
		$default = ($postArray['default_set'])?1:0;
		if(!$fileArray['error']) {
		
			if(($fileArray["type"] == 'image/jpeg') || ($fileArray["type"] == 'image/pjpeg'))	{
				$suf = ".jpg";
			}
			elseif($fileArray["type"] == 'image/gif')	{
				$suf = ".gif";
			}
			elseif($fileArray["type"] == 'image/png')	{
				$suf = ".png";
			}
			$filename = WSS_UPLOADS_DIR.'/'.$id.$suf;
			move_uploaded_file($fileArray['tmp_name'], $filename);
			image_resize($filename,500,auto,false,'thumb',WSS_UPLOADS_DIR);
			$wpdb->update($prefix.'wss_images',array( 'guid' => $id.$suf ), array( 'id' => $id ) );
			
		}
		//$update_image = $wpdb->query("Update ".$prefix."wss_images Set pages = ".$pages." where id = ".$id."");
		$update_image = $wpdb->query("Update ".$prefix."wss_images Set name = '".$name."', content = '".$content."', link = '".$alter_text."', pages = ".$pages." where id = ".$id."");
		//$wpdb->update($prefix.'wss_images',array( 'content' => $content, 'link' => $link, 'pages' => $pages, 'default' => $default ), array( 'id' => $id ) );
		
		$res['status'] = 'success'; $res['msg'] = 'Values updated successfully';
		
		return $res;
	}
	
	function edit_logo($postArray, $fileArray){
		global $wpdb;
		$prefix = $wpdb->prefix;
		
		$id = $postArray['id'];
		$type = $postArray['imglocation'];
		$link = $postArray['image_link'];
		$alter_text = $postArray['alter_text'];
		$logo_desc_content = $postArray['logo_desc_content'];
			
		if(!$fileArray['error']) {
		
			if(($fileArray["type"] == 'image/jpeg') || ($fileArray["type"] == 'image/pjpeg'))	{
				$suf = ".jpg";
			}
			elseif($fileArray["type"] == 'image/gif')	{
				$suf = ".gif";
			}
			elseif($fileArray["type"] == 'image/png')	{
				$suf = ".png";
			}
			
			$filename = WSS_UPLOADS_DIR.'/logos/'.$id.$suf;
			move_uploaded_file($fileArray['tmp_name'], $filename);
			image_resize($filename,150,86,false,'thumb',WSS_UPLOADS_DIR.'/logos');
			$wpdb->update($prefix.'wss_logos',array( 'guid' => $id.$suf ), array( 'id' => $id ) );
			
		}
		
		$wpdb->update($prefix.'wss_logos',array( 'link' => $link, 'alter_text' => $alter_text, 'type' => $type,'content' => $logo_desc_content ), array( 'id' => $id ) );
		
		$res['status'] = 'success'; $res['msg'] = 'Values updated successfully';
		
		return $res;
	}
	
	function delete_image($id){
		global $wpdb;
		$prefix = $wpdb->prefix;
		$img = $this->get_image($id);
		@unlink(WSS_UPLOADS_DIR.'/'.$img->guid);
		@unlink(WSS_UPLOADS_DIR.'/'.preg_replace('/(.*)(\.[\w\d]{3})/', '$1-thumb$2', $img->guid));
		return $wpdb->query( $wpdb->prepare('DELETE FROM '.$prefix.'wss_images WHERE id = '.$id,''));
	}
	
	function delete_logo($id){
		global $wpdb;
		$prefix = $wpdb->prefix;
		$img = $this->get_logo($id);
		@unlink(WSS_UPLOADS_DIR.'/logos/'.$img->guid);
		@unlink(WSS_UPLOADS_DIR.'/logos/'.preg_replace('/(.*)(\.[\w\d]{3})/', '$1-thumb$2', $img->guid));
		return $wpdb->query( $wpdb->prepare('DELETE FROM '.$prefix.'wss_logos WHERE id = '.$id,'' ));
	}
	
	function show_slider(){
		global $wpdb, $post;
		$prefix = $wpdb->prefix;
		$res = $wpdb->get_results('SELECT content, link, guid, alter_text FROM '.$prefix.'wss_images WHERE pages REGEXP '.$post->ID);
		if(!$res){
			$res = $wpdb->get_results('SELECT content, link, guid, alter_text FROM '.$prefix.'wss_images WHERE `default` = 1');
		}
		return $res;		
	}
	
	function show_logos($location){
		global $wpdb, $post;
		$prefix = $wpdb->prefix;
		$res = $wpdb->get_results("SELECT content,link, guid, alter_text FROM ".$prefix."wss_logos WHERE `type` = '".$location."'");
		return $res;		
	}
	
	function postTile($title){
		$titleres = mysql_query("select post_title from wp_posts where id = '".$title."'");
		$title 	  = mysql_fetch_assoc($titleres);	
		return $title[post_title];
	}
	function postId($id){
		$pagesres = mysql_query("select pages from wp_wss_images where pages LIKE '%".$id."%'");	
		$pages 	  = mysql_num_rows($pagesres);
		return $pages;
	}
	
}
?>