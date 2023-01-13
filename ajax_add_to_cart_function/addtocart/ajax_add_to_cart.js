jQuery(document).ready(function($) {

    $(document).on('click', '.et_pb_module .et_shop_image .et_overlay .addtocart_button', function(e){ 
    e.preventDefault();
                
    $thisbutton = $(this),
                productClass = $thisbutton.parents("li").attr('class').match(/post\-\d+/gi)[0];
                product_id = productClass.split('-')[1];
                product_qty = 1,
                variation_id = 0;
    var data = {
            action: 'ql_woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
        };
    $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function (response) {
                $thisbutton.removeClass('added').addClass('loading');
                $thisbutton.text('Please wait');
            },
            complete: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
                
                setTimeout(function() { 
                    $thisbutton.text('The product has been added');
                }, 2000);
                setTimeout(function() { 
                    $thisbutton.text('Add to cart');
                }, 4000);
                
            }, 
            success: function (response) { 
                
                if (response.error & response.product_url) {
                    window.location = response.product_url;
                    return;
                } else { 
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                    UpdateCart();
                } 
            }, 
        });
    function UpdateCart(){
        var dataupdate = {
            action: 'ql_woocommerce_ajax_update_cart',
        };
        $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: dataupdate,
            success: function (data) {
                $('div#et-secondary-menu a.et-cart-info').html(data);
            }
        });
    }
 
     });
      
});