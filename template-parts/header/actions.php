<?php

/**
 * Template part for displaying the header-actions
 * Template Version: 6.3.1
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
<?php if (is_active_sidebar('top-nav-search')) : ?>
  <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'search-toggler')); ?> d-<?= esc_attr(apply_filters('bootscore/class/header/search/breakpoint', 'lg')); ?>-none <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'search-toggler')); ?> search-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search" aria-label="<?php esc_attr_e( 'Search toggler', 'bootscore' ); ?>">
    <?= wp_kses_post(apply_filters('bootscore/icon/search', '<i class="fa-solid fa-magnifying-glass"></i>')); ?> <span class="visually-hidden-focusable">Search</span>
  </button>
<?php endif; ?>
