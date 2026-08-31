<?php

/**
 * Template part for displaying the header-actions
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<!-- Searchform large -->
<?php if (is_active_sidebar('top-nav-search')) : ?>
  <div class="d-none d-<?= esc_attr(apply_filters('bootscore/class/header/search/breakpoint', 'lg')); ?>-block <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'searchform')); ?> nav-search-lg">
    <?php dynamic_sidebar('top-nav-search'); ?>
  </div>
<?php endif; ?>

<!-- Search toggler mobile -->
<?php if (apply_filters('bootscore/sidebar/has_widgets', is_active_sidebar('top-nav-search'), 'top-nav-search')) : ?>
  <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'search-toggler')); ?> d-<?= esc_attr(apply_filters('bootscore/class/header/search/breakpoint', 'lg')); ?>-none <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'search-toggler')); ?> search-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search" aria-label="<?php esc_attr_e( 'Search toggler', 'bootscore' ); ?>">
    <?= wp_kses( apply_filters('bootscore/icon/chevron-up', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) )); ?><span class="visually-hidden-focusable">Search</span>
  </button>
<?php endif; ?>
