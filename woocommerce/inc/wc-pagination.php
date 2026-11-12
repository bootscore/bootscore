<?php

/**
 * WooCommerce Archive Pagination
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Pagination
 *
 * Same markup as the theme pagination (inc/pagination.php). Uses paginate_links() like WooCommerce,
 * so it also works for paginated [products] shortcodes.
 */
function bootscore_wc_pagination() {
  $total   = wc_get_loop_prop('total_pages');
  $current = wc_get_loop_prop('current_page');

  if ($total <= 1) {
    return;
  }

  $base = esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));

  $links = paginate_links(apply_filters('woocommerce_pagination_args', array(
    'base'      => $base,
    'format'    => '',
    'add_args'  => false,
    'current'   => max(1, $current),
    'total'     => $total,
    'prev_text' => '<span aria-hidden="true">&laquo;</span><span class="visually-hidden">' . esc_html__('Previous Page', 'bootscore') . '</span>',
    'next_text' => '<span aria-hidden="true">&raquo;</span><span class="visually-hidden">' . esc_html__('Next Page', 'bootscore') . '</span>',
    'type'      => 'array',
    'end_size'  => 1,
    'mid_size'  => 1,
  )));

  if (!is_array($links)) {
    return;
  }
  ?>
  <nav aria-label="<?php esc_attr_e('Product Pagination', 'woocommerce'); ?>">
    <ul class="pagination <?= esc_attr(apply_filters('bootscore/class/woocommerce/pagination', 'justify-content-center')); ?>">
      <?php foreach ($links as $link) :
        // paginate_links() marks the current page with the class "current" and aria-current="page"
        $is_current = (bool) preg_match('/class="[^"]*\bcurrent\b/', $link);
        $link       = preg_replace('/class="([^"]*)"/', 'class="page-link"', $link, 1);
        ?>
        <li class="page-item<?= $is_current ? ' active' : ''; ?>"><?= wp_kses_post($link); ?></li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <?php
}

remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action('woocommerce_after_shop_loop', 'bootscore_wc_pagination', 10);
