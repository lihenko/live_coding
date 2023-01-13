jQuery(document).ready(function($) {
	html = '<div class="et_pb_button addtocart_button">Add to cart</div>';
	$('.et_pb_module .et_overlay').html(html);
	$('.et_pb_module .et_shop_image').each(function( index ) {
	  link = $(this).parents('a');
	  $(this).insertBefore(link);
	});
	
});

