<?php

/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 7.0.1
 */

if (!defined('ABSPATH')) {
  exit;
}

?>
<form role="search" method="get" class="searchform woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
  <div class="input-group">
    <input class="form-control" type="search" id="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>" class="search-field field form-control" placeholder="<?php echo esc_attr__('Search products...', 'woocommerce'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <label class="visually-hidden-focusable" for="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>"><?php esc_html_e('Search for:', 'woocommerce'); ?></label>
    <input type="hidden" name="post_type" value="product" />
    <button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>" class="input-group-text btn btn-outline-secondary <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>"><i class="fa-solid fa-magnifying-glass"></i><span class="visually-hidden-focusable">Search</span></button>
  </div>
</form>