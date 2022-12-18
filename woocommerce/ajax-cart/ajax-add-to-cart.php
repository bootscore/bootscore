<?php


// Add the product name as data argument to Ajax add to cart buttons
add_filter( "woocommerce_loop_add_to_cart_args", "filter_wc_loop_add_to_cart_args", 20, 2 );
function filter_wc_loop_add_to_cart_args( $args, $product ) {
    if ( $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ) {
        $args['attributes']['product-title'] = $product->get_name();
    }
    return $args;
}


/*
    https://aceplugins.com/ajax-add-to-cart-button-on-the-product-page-woocommerce/
*/

// JS for AJAX Add to Cart handling
function bootscore_product_page_ajax_add_to_cart_js() {
?>
  <script type="text/javascript" charset="UTF-8">
    jQuery(function($) {

      $('form.cart:not(.product-type-external form.cart)').on('submit', function(e) {
        e.preventDefault();
        $(document.body).trigger('adding_to_cart', []);
        
        var form = $(this);
        /*
        form.block({
          message: null,
          overlayCSS: {
            background: '#fff',
            opacity: 0.6
          }
        });
        */

        //$('.single_add_to_cart_button').addClass('loading');
        // Add loading class to button, hide in grouped products if product is out of stock
        $(this).find('.single_add_to_cart_button:not(.outofstock .single_add_to_cart_button)').addClass('loading');

        var formData = new FormData(form[0]);
        formData.append('add-to-cart', form.find('[name=add-to-cart]').val());

        // Ajax action.
        $.ajax({
          url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ace_add_to_cart'),
          data: formData,
          type: 'POST',
          processData: false,
          contentType: false,
          complete: function(response) {
            response = response.responseJSON;

            if (!response) {
              return;
            }

            if (response.error && response.product_url) {
              window.location = response.product_url;
              return;
            }

            // Redirect to cart option
            if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
              window.location = wc_add_to_cart_params.cart_url;
              return;
            }

            var $thisbutton = form.find('.single_add_to_cart_button'); //

            // Trigger event so themes can refresh other areas.
            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);

            // Remove existing notices
            $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();

            // Add new notices to offcanvas
            $('.woocommerce-mini-cart').prepend(response.fragments.notices_html);

            form.unblock();
          }
        });


      });



      $('a.ajax_add_to_cart:not(.outofstock a.ajax_add_to_cart)').on('click', function(e) {
        
        e.preventDefault();


        $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();

        //var prod_title = ($(this).parents('.card-body').parent().find('.woocommerce-loop-product__title').show().html());

        //var prod_name = data-product_name;
//var prod_title = ('data-product_name').text('data-product_name');
        
        var prod_title = "";

  prod_title = $(this).attr("product-title");
  //alert(title);
  //return false;


        $(document.body).trigger('adding_to_cart', []);

        var $thisbutton = $(this);
        try {
            var href = $thisbutton.prop('href').split('?')[1];

            if (href.indexOf('add-to-cart') === -1) return;
        } catch (err) {
            return;
        }

        //e.preventDefault();

        var product_id = href.split('=')[1];

        var data = {
            product_id: product_id
        };

        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
        
        $.ajax({
          
          type: 'post',
            url: wc_add_to_cart_params.wc_ajax_url.replace(
                '%%endpoint%%',
                'add_to_cart'
            ),
            data: data,

            complete: function(response) {
                $thisbutton.addClass('added').removeClass('loading');

            },

          success: function(response) {

              
             
              if (response.error & response.product_url) {
                  //window.location = response.product_url;
                  console.log( response.error );
                 
                  //return false;
              }else {
                  $(document.body).trigger('added_to_cart', [
                      response.fragments,
                      response.cart_hash,
                      $thisbutton
                  ]);

                  console.log( "Error-: "+ response.error );

  
                  //Remove existing notices
                  $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();

                  if( response.error == true ){
                    
                    var notice = `<div class='woocommerce-message alert alert-danger'><?php _e('You cannot add another','bootscore'); ?> “${prod_title}” <?php _e('to your cart.','bootscore'); ?></div>`;
                   
                   

                  }else{
                    
                    var notice = `<div class="woocommerce-message alert alert-success">“${prod_title}” <?php _e('has been added to your cart.','bootscore'); ?></div>`;

                  }

                  

                  // Add new notices to offcanvas
                  setTimeout(function () {
                    $('.woocommerce-mini-cart').prepend(notice);
                    
                  }, 100);

                  
                  
                  
                  // console.log("notice", notice );
                  //$('.woocommerce-mini-cart').prepend(response.fragments.notices_html);

              }
          }

        });


      });

      
      /*
  // Add loading spinner to add_to_cart_button 
  $('.single_add_to_cart_button:not(.product_type_variable.single_add_to_cart_button):not(.product_type_grouped.single_add_to_cart_button):not(.product_type_external.single_add_to_cart_button):not(.product-type-external .single_add_to_cart_button):not(.product-type-external .single_add_to_cart_button)').click(function () {
    $(this).prepend('<span class="spinner-border spinner-border-sm me-1"></span>');
  });
  */
      
      /*
        // Add loading spinner to add_to_cart_button 
  $('.single_add_to_cart_button:not(.product_type_variable.single_add_to_cart_button):not(.product_type_grouped.single_add_to_cart_button):not(.product_type_external.single_add_to_cart_button):not(.product-type-external .single_add_to_cart_button):not(.product-type-external .single_add_to_cart_button)').click(function () {
    $(this).prepend('<div class="btn-loader"><span class="spinner-border spinner-border-sm"></span></div>');
  });
  */
      
  // Add loading spinner to add_to_cart_button
  $('.single_add_to_cart_button').prepend('<div class="btn-loader"><span class="spinner-border spinner-border-sm"></span></div>');

      //$('.outofstock .btn').attr('target', '_blank');

  $('body').on('added_to_cart', function () {
    // Open offcanvas-cart when cart is loaded
    $('#offcanvas-cart').offcanvas('show');
    // Remove loading spinner
    //$('.single_add_to_cart_button .btn-loader').remove();
  });

  // Hide alert in offcanvas-cart when offcanvas is closed
  $('#offcanvas-cart').on('hidden.bs.offcanvas', function () {
    $('#offcanvas-cart .woocommerce-message').remove();
  });
      



    });
  </script>
<?php
}
add_action('wp_footer', 'bootscore_product_page_ajax_add_to_cart_js');
// JS for AJAX Add to Cart handling End


// Add to cart handler
function bootscore_ajax_add_to_cart_handler() {
  WC_Form_Handler::add_to_cart_action();
  WC_AJAX::get_refreshed_fragments();
}
add_action('wc_ajax_ace_add_to_cart', 'bootscore_ajax_add_to_cart_handler');
add_action('wc_ajax_nopriv_ace_add_to_cart', 'bootscore_ajax_add_to_cart_handler');

// Remove WC Core add to cart handler to prevent double-add
remove_action('wp_loaded', array('WC_Form_Handler', 'add_to_cart_action'), 20);
// Add to cart handler End


// Add fragments for notices
function bootscore_ajax_add_to_cart_add_fragments($fragments) {
  $all_notices  = WC()->session->get('wc_notices', array());
  $notice_types = apply_filters('woocommerce_notice_types', array('error', 'success', 'notice'));

  // Comment or delete this to hide Alerts
  ob_start();
  foreach ($notice_types as $notice_type) {
    if (wc_notice_count($notice_type) > 0) {
      wc_get_template("notices/{$notice_type}.php", array(
        'notices' => array_filter($all_notices[$notice_type]),
      ));
    }
  }
  $fragments['notices_html'] = ob_get_clean();
  // Comment or delete this to hide Alerts

  wc_clear_notices();

  return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'bootscore_ajax_add_to_cart_add_fragments');


// Stop redirecting after stock error
add_filter( 'woocommerce_cart_redirect_after_error', '__return_false' );



// Minicart Header
if (!function_exists('bs_mini_cart')) :
  function bs_mini_cart($fragments) {

    ob_start();
    $count = WC()->cart->cart_contents_count; ?>
    <span class="cart-content">
      <?php if ($count > 0) { ?>
        <span class="cart-content-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"><?php echo esc_html($count); ?></span><span class="cart-total ms-1 d-none d-md-inline"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
      <?php } ?>
    </span>

  <?php
    $fragments['span.cart-content'] = ob_get_clean();

    return $fragments;
  }
  add_filter('woocommerce_add_to_cart_fragments', 'bs_mini_cart');

endif;
// Minicart Header End
