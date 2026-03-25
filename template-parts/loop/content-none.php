<?php

/**
 * Template part for displaying a message that posts cannot be found
 * Template Version: 6.4.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>

<header class="page-header mb-4">
  <h1 class="page-title"><?php esc_html_e('Nothing Found for', 'bootscore'); ?> <span class="text-body-secondary"><?= esc_html(get_search_query()); ?></span></h1>
</header>

<section class="no-results not-found">
  <div class="page-content">
    <?php if (is_search()) : ?>
      <p class="alert alert-info mb-4">
        <?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'bootscore'); ?>
      </p>
      <?php get_search_form(); ?>
    <?php endif; ?>
  </div>
</section>
