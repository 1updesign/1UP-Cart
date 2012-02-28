<?php
function get_catID($slug) {
	$idObj = get_category_by_slug($slug); 
	return $id = $idObj->term_id;
}

function get_pageID($slug) {
	$pages = explode(",", trim($slug));
	foreach($pages as $p) {
		$idObj = get_page_by_title($p);
		$id .= $idObj->ID.",";
	}
	return $id;
}

function get_pageID_as_array($slug) {
	$pages = explode(",", $slug);
	$id_array = array();
	foreach($pages as $p) {
		$id = get_page_by_title($p);
		$id_array[] = $id->ID;
	}
	return $id_array;
}

function get_postID($post_name) {
	wp_reset_query();	
	$args = array(post_type => 'artists', name => $post_name);
	$loop = get_posts( $args );
	return $loop[0]->ID;
}
?>