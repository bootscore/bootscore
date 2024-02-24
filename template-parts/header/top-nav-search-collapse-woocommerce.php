<?php

/**
 * Template part for displaying the top-nav searchform collapse widget if WooCommerce is installed
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<!-- Top Nav Search Collapse -->
<?php if (is_active_sidebar('top-nav-search')) : ?>
  <div class="collapse <?= apply_filters('bootscore/container_class', 'container', 'top-nav-search-collapse-woocommerce'); ?> mb-2" id="collapse-search">
    <?php dynamic_sidebar('top-nav-search'); ?>
  </div>
<?php endif; ?>
