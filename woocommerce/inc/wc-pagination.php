<?php

/**
 * WooCommerce Archive Pagination
 *
 * @package Bootscore
 * @version 6.5.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Custom WooCommerce pagination
 */
function bootscore_wc_pagination() {

  $total   = wc_get_loop_prop( 'total_pages' );
  $current = wc_get_loop_prop( 'current_page' );
  $base    = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
  $format  = '';

  if ( $total <= 1 ) {
    return;
  }
  ?>
  <nav aria-label="<?php esc_attr_e( 'Product Pagination', 'woocommerce' ); ?>">
    <?php

    $paginate_links = paginate_links(
      apply_filters(
        'woocommerce_pagination_args',
        array(
          'base'      => $base,
          'format'    => $format,
          'add_args'  => false,
          'current'   => max( 1, $current ),
          'total'     => $total,
          'prev_text' => '&laquo;',
          'next_text' => '&raquo;',
          'type'      => 'array',
          'end_size' => 1,
          'mid_size'  => 1,
        )
      )
    );

    if ( is_array( $paginate_links ) ) {
      ?>
      <ul class="pagination justify-content-center">
        <?php
        foreach ( $paginate_links as $paginate_link ) {

          // Check if this is the current page.
          $is_current = str_contains( $paginate_link, 'current' );

          // Replace 'page-numbers' with 'page-link' and remove 'current'.
          $paginate_link = str_replace(
            array( 'page-numbers', 'current' ),
            array( 'page-link', '' ),
            $paginate_link
          );
          ?>

          <li class="page-item<?php echo $is_current ? ' active' : ''; ?>">
            <?php echo wp_kses_post( $paginate_link ); ?>
          </li>

          <?php
        }
        ?>
      </ul>
      <?php
    }
    ?>
  </nav>
  <?php
}


/**
 * Remove default WooCommerce pagination
 */
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );


/**
 * Add custom pagination
 */
add_action( 'woocommerce_after_shop_loop', 'bootscore_wc_pagination', 10 );
