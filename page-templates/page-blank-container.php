<?php

/**
 * Template Name: Blank with container (Deprecated, this file will be removed in v7)
 * Template Version: 6.4.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'page-blank-with-container')); ?>">
    <div id="primary" class="content-area">

      <main id="main" class="site-main">

        <div class="entry-content">
          <?php the_post(); ?>
          <?php the_content(); ?>
        </div>

      </main>

    </div>
  </div>

<?php
get_footer();
