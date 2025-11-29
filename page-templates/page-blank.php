<?php

/**
 * Template Name: Blank
 * Template Version: 6.3.1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content">
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