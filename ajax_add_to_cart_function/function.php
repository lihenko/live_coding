/* Add to cart CSS + Ajax start */


function addtocart_scripts() {

	$theme_version = et_get_theme_version();

	wp_enqueue_style( 'addtocart-style', get_template_directory_uri() . '/addtocart/addtocart.css', array(), $theme_version );

	wp_enqueue_script('addtocart-script', get_template_directory_uri() . '/addtocart/addtocart.js', array('jquery'), $theme_version, true );


	if (function_exists('is_product')) {  
       wp_enqueue_script('ajax_add_to_cart', get_template_directory_uri() . '/addtocart/ajax_add_to_cart.js', array('jquery'), $theme_version, true );
       wp_localize_script( 'ajax_add_to_cart', 'wc_add_to_cart_params', array(
        'ajax_url' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
    ) );
    }


}
add_action( 'wp_enqueue_scripts', 'addtocart_scripts', 999999 );


add_action('wp_ajax_ql_woocommerce_ajax_add_to_cart', 'ql_woocommerce_ajax_add_to_cart'); 
add_action('wp_ajax_nopriv_ql_woocommerce_ajax_add_to_cart', 'ql_woocommerce_ajax_add_to_cart');          
function ql_woocommerce_ajax_add_to_cart() {  
    $product_id = apply_filters('ql_woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('ql_woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id); 
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) { 
        do_action('ql_woocommerce_ajax_added_to_cart', $product_id);
            if ('yes' === get_option('ql_woocommerce_cart_redirect_after_add')) { 
                wc_add_to_cart_message(array($product_id => $quantity), true); 
            } 
            WC_AJAX :: get_refreshed_fragments(); 
            } else { 
                $data = array( 
                    'error' => true,
                    'product_url' => apply_filters('ql_woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));
                echo wp_send_json($data);
            }
    wp_die();
}

add_action( 'wp_ajax_ql_woocommerce_ajax_update_cart', 'ajax_update_cart_handler' );
add_action('wp_ajax_nopriv_ql_woocommerce_ajax_update_cart', 'ajax_update_cart_handler'); 
/**
 * Add AJAX Shortcode when cart contents update
 */
function ajax_update_cart_handler() {
 
    
	    $cart_count = WC()->cart->cart_contents_count;
	    
		 echo '<span>' . $cart_count . ' Items</span>';
     
    wp_die();
}        