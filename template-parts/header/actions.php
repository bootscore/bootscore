<?php

/**
 * Template part for displaying the header-actions
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.1.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<!-- Searchform large -->
<?php if (is_active_sidebar('top-nav-search')) : ?>
  <div class="d-none d-<?= apply_filters('bootscore/class/header/search/breakpoint', 'lg'); ?>-block <?= apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'searchform'); ?> nav-search-lg">
    <?php dynamic_sidebar('top-nav-search'); ?>
  </div>
<?php endif; ?>

<!-- Search toggler mobile -->
<?php if (is_active_sidebar('top-nav-search')) : ?>
  <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'search-toggler'); ?> d-<?= apply_filters('bootscore/class/header/search/breakpoint', 'lg'); ?>-none <?= apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'search-toggler'); ?> search-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search" aria-label="<?php esc_attr_e( 'Search toggler', 'bootscore' ); ?>">
    <?= apply_filters('bootscore/icon/search', '<i class="fa-solid fa-magnifying-glass"></i>'); ?> <span class="visually-hidden-focusable">Search</span>
  </button>
<?php endif; ?>
