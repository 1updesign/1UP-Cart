<?php
/*
	Template Name: Cart
*/
?>
<?php get_header(); ?>
<link rel="stylesheet" href="<?php bloginfo(stylesheet_directory); ?>/cart.css" media="screen"></style>
<?php get_sidebar(); ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("#checkout_button").click(function(){
			$('#cart_data').attr("value", JSON.stringify(simpleCart));
		})
	})
</script>
<div class="content" id="checkout">
	<h2 class='title'>CART ITEMS</h2>
	<h2>CART ITEMS</h2>
	<div class="simpleCart_items"></div>
	<h2 class="clearboth">SHIPPING</h2>
	<div id="shipping">
		<p>Total shipping cost: £9.00</p>
		<p>Some blurb about shipping times and stuff</p>
	</div>
	<h2>FINAL TOTAL</h2>
		<span class="simpleCart_finalTotal"></span>
	<div class="checkout right">
		<form action="../wp-content/plugins/cart/assets/php/expresscheckout.php" method="get" accept-charset="utf-8">
			<input type="hidden" name="cart_data" value="" id="cart_data">
			<p><input id="checkout_button" type="submit" class="" value="Checkout &rarr;"></p>
		</form>
	</div>
</div>
<?php get_footer(); ?>