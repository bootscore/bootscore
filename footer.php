<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<footer>

  <?php if (is_active_sidebar('footer-top')) : ?>
    <div class="<?= apply_filters('bootscore/class/footer/top', 'bg-body-tertiary border-bottom py-5'); ?> bootscore-footer-top">
      <div class="<?= apply_filters('bootscore/class/container', 'container', 'footer-top'); ?>">  
        <?php dynamic_sidebar('footer-top'); ?>
      </div>
    </div>
  <?php endif; ?>
  
  <div class="<?= apply_filters('bootscore/class/footer', 'bg-body-tertiary pt-5 pb-4'); ?> bootscore-footer">
    <div class="<?= apply_filters('bootscore/class/container', 'container', 'footer'); ?>">  

      <div class="row g-3 mb-3">

        <?php if (is_active_sidebar('footer-1')) : ?>
          <div class="<?= apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-1'); ?>">
            <?php dynamic_sidebar('footer-1'); ?>
          </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer-2')) : ?>
          <div class="<?= apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-2'); ?>">
            <?php dynamic_sidebar('footer-2'); ?>
          </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer-3')) : ?>
          <div class="<?= apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-3'); ?>">
            <?php dynamic_sidebar('footer-3'); ?>
          </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer-4')) : ?>
          <div class="<?= apply_filters('bootscore/class/footer/col', 'col-6 col-lg-3', 'footer-4'); ?>">
            <?php dynamic_sidebar('footer-4'); ?>
          </div>
        <?php endif; ?>

      </div>

      <!-- Bootstrap 5 Nav Walker Footer Menu -->
      <?php get_template_part('template-parts/footer/footer-menu'); ?>

    </div>
  </div>

  <div class="<?= apply_filters('bootscore/class/footer/info', 'bg-body-tertiary text-body-secondary border-top py-2 text-center'); ?> bootscore-footer-info">
    <div class="<?= apply_filters('bootscore/class/container', 'container', 'footer-info'); ?>">
      <?php if (is_active_sidebar('footer-info')) : ?>
        <?php dynamic_sidebar('footer-info'); ?>
      <?php endif; ?>
      <div class="small bootscore-copyright"><span class="cr-symbol">&copy;</span>&nbsp;<?= date('Y'); ?> <?php bloginfo('name'); ?></div>
    </div>
  </div>

</footer>

<!-- To top button -->
<a href="#" class="<?= apply_filters('bootscore/class/footer/to_top_button', 'btn btn-primary shadow'); ?> position-fixed z-1 top-button"><i class="fa-solid fa-chevron-up"></i><span class="visually-hidden-focusable">To top</span></a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>
