<?php
/*
Plugin Name: Cart
Description: Includes a Jquery cart and Paypal Express Checkout
Version: The Plugin's Version Number, e.g.: 0.1
Author: 1UP
Author URI: http://1updesign.org
License: GPL2
*/
?>
<?php 

// Include the useful wp-functions

require (ABSPATH.'wp-content/plugins/cart/assets/php/functions.php');

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'create_cart_pages');

function include_cart_scripts_and_styles() {
	wp_enqueue_script( 'jQuery' );
	wp_register_script( 'simpleCart', WP_PLUGIN_URL.'/cart/assets/js/simpleCart.js');
	wp_enqueue_script( 'simpleCart' );
	wp_register_script( 'json2', WP_PLUGIN_URL.'/cart/assets/js/json2.js');
	wp_enqueue_script( 'json2' );
	wp_register_script( 'simpleCart_Config', WP_PLUGIN_URL.'/cart/assets/js/simpleCart_config.js');
	wp_enqueue_script( 'simpleCart_Config' );
}

add_action('wp', 'include_cart_scripts_and_styles');

function get_cart_total() {
	echo "<span class='simpleCart_total'></span>";
}

function get_cart_items_total() {
	echo "<span class='simpleCart_quantity'></span>";
}

function get_cart_empty_button() {
	echo "<a href='javascript:;' class='simpleCart_empty'>Empty cart</a>";
}

function get_checkout_button() {
	?>
	<a href='javascript:;' class='simpleCart_checkout'>
		<div id="checkout_button">
			<p class="left">Checkout with Payal</p>
			<img src='https://www.paypal.com/en_US/i/logo/PayPal_mark_37x23.gif'>
		</div>
	</a>
	<?php
	echo "";
}

function list_item($name, $price, $shipping=FALSE) {
?>
	<div class="simpleCart_shelfItem">
		<span class="item_name"><?php echo $name; ?></span>
		<span class="item_price"><?php echo $price; ?></span>
		<?php if(!$shipping == FALSE) : ?> <input type="hidden" class="item_shipping" value="<?php echo $shipping;   ?>" /> <?php endif; ?>
		<input type="hidden" class="item_quantity" value="1" />
		<a class="item_add" href="javascript:;"> Add to Cart </a>
	</div>
<?php
}

function simple_list_item($name, $price, $format, $permalink, $shipping=FALSE) {
?>
<a href="javascript:;" onclick="simpleCart.add( 'name=<?php echo $name; ?>' , 'price=<?php echo $price; ?>' , 'quantity=1', 'format=<?php echo $format; ?>', 'permalink=<?php echo $permalink; ?>' );">
	<img src="<?php bloginfo('stylesheet_directory'); ?>/images/cart.png">
	<span>Add To Cart</span>
	</a>
<?php
}

function create_cart_pages() {
	global $wpdb;
	//An array of all the pages required for the checkout and their template files
	$cart_pages = array(
		"review" => array(
			"post_title" => "Review",
			"template" => "review.php"
		),
		"confirm" => array(
			"post_title" => "Confirm",
			"template" => "confirm.php"
		),
		"cart" => array(
			"post_title" => "Cart",
			"template" => "cart.php"
		)
	);
	
	foreach($cart_pages as $key => $page) {
		//Copy the template files for the cart over
		if(!copy(ABSPATH.'wp-content/plugins/cart/templates/'.$page['template'], ABSPATH."wp-content/themes/".get_current_theme()."/".$page['template'])){
			echo "failed to copy ".$page['template']."\n";
		}
		
		$the_page_title = $page['post_title'];
		//get the page deets so we can see if it already exists.  
		$current_page = get_page_by_title( $the_page_title );
		//if it doesn't then create a post
	  	  if ( ! $current_page ) {
		        // Create post object
		        $_p = array();
		        $_p['post_title'] = $the_page_title;
		        $_p['post_content'] = "";
		        $_p['post_status'] = 'publish';
		        $_p['post_type'] = 'page';
		        $_p['comment_status'] = 'closed';
		        $_p['ping_status'] = 'closed';
		        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
				
		        // Insert the post into the database
		        $the_page_id = wp_insert_post( $_p );
				// Once the post is created set the template
				update_post_meta($the_page_id, 'page_template', $page['template']);
		    }else{
				//It's in the trash, get it out of the trash then change the template....
				$the_page_id = $current_page->ID;

				//make sure the page is not trashed...
				$current_page->post_status = 'publish';
				
				$the_page_id = wp_update_post( $current_page );
				
				update_post_meta($the_page_id, '_wp_page_template', $page['template']);
			}
			//Add the page IDs to the $pages array
			$cart_pages[$key]['id'] .= $the_page_id;
		}
		return $cart_pages;
	}
	//Exclude the cart pages when using the get_pages() function
	add_filter('get_pages', 'exclude_from_get_pages', 0, 3);
	 function exclude_from_get_pages($results){	
		$excluded = get_pageID_as_array("confirm,review,cart");
			foreach($results as $key => $page) {
				if(in_array($page->ID,$excluded)) unset($results[$key]); 
			}
		return $results;
	 }
	//Exclude the cart pages when using the wp_list_pages() function
	add_filter('wp_list_pages_excludes', 'exclude_from_wp_list_pages');
	function exclude_from_wp_list_pages($exclude_array){
		global $pages;
	    $exclude_array = $exclude_array + array(get_pageID("confirm,review"));
	    return $exclude_array;
	}
?>