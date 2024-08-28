<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if (!defined('ABSPATH')) {
  exit;
}

$total   = isset($total) ? $total : wc_get_loop_prop('total_pages');
$current = isset($current) ? $current : wc_get_loop_prop('current_page');
$base    = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
$format  = isset($format) ? $format : '';

if ($total <= 1) {
  return;
}
?>
<nav aria-label="<?php esc_attr_e( 'Product Pagination', 'woocommerce' ); ?>">
  <?php
  $paginate_links =  paginate_links(
    apply_filters(
      'woocommerce_pagination_args',
      array(
        'base'         => $base,
        'format'       => $format,
        'add_args'     => false,
        'current'      => max(1, $current),
        'total'        => $total,
        'prev_text'    => '&laquo;',
        'next_text'    => '&raquo;',
        'type'         => 'array',
        'end_size'     => 1,
        'mid_size'     => 1,
      )
    )
  );

  if (is_array($paginate_links)) {
  ?>
    <ul class="pagination justify-content-center">
      <?php
      foreach ($paginate_links as $paginate_link) {
        // Replace 'page-numbers' with 'page-link' and 'current' with 'active'.
        $paginate_link = str_replace(array('page-numbers', 'current'), array('page-link', 'active'), $paginate_link);
      ?>

        <li class="page-item">
          <?php
          echo wp_kses_post($paginate_link);
          ?>
        </li>

      <?php
      }
      ?>
    </ul>
  <?php
  }
  ?>
</nav>
