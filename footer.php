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

function bootscore_count_active_footer_widgets(): int {
    $count = 0;
    for ($i = 1; $i <= 4; $i++) {
        if (is_active_sidebar('footer-' . $i)) {
            $count++;
        }
    }
    return $count;
}

function bootscore_get_footer_widget_class(): string {
    $widget_count = bootscore_count_active_footer_widgets();
    return match ($widget_count) {
        1 => 'col-12',
        2 => 'col-6',
        3 => 'col-4',
        default => 'col-3',
    };
}
?>


<footer class="bootscore-footer">

    <?php if (is_active_sidebar('footer-top')) : ?>
        <div class="<?= apply_filters('bootscore/class/footer/top', 'bg-body-tertiary border-bottom py-5'); ?> bootscore-footer-top">
            <div class="<?= apply_filters('bootscore/class/container', 'container', 'footer-top'); ?>">
                <?php dynamic_sidebar('footer-top'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="<?= apply_filters('bootscore/class/footer/columns', 'bg-body-tertiary pt-5 pb-4'); ?> bootscore-footer-columns">
        <div class="<?= apply_filters('bootscore/class/container', 'container', 'footer-columns'); ?>">

            <div class="row">
                <div class="<?= apply_filters('bootscore/class/footer/col', bootscore_get_footer_widget_class(), 'footer-1'); ?>">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <?php dynamic_sidebar('footer-1'); ?>
                    <?php endif; ?>
                </div>

                <div class="<?= apply_filters('bootscore/class/footer/col', bootscore_get_footer_widget_class(), 'footer-2'); ?>">
                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <?php dynamic_sidebar('footer-2'); ?>
                    <?php endif; ?>
                </div>

                <div class="<?= apply_filters('bootscore/class/footer/col', bootscore_get_footer_widget_class(), 'footer-3'); ?>">
                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <?php dynamic_sidebar('footer-3'); ?>
                    <?php endif; ?>
                </div>

                <div class="<?= apply_filters('bootscore/class/footer/col', bootscore_get_footer_widget_class(), 'footer-4'); ?>">
                    <?php if (is_active_sidebar('footer-4')) : ?>
                        <?php dynamic_sidebar('footer-4'); ?>
                    <?php endif; ?>
                </div>
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
            <div class="small bootscore-copyright"><span
                        class="cr-symbol">&copy;</span>&nbsp;<?= date('Y'); ?> <?php bloginfo('name'); ?></div>
        </div>
    </div>

</footer>

<!-- To top button -->
<a href="#"
   class="<?= apply_filters('bootscore/class/footer/to_top_button', 'btn btn-primary shadow'); ?> position-fixed z-2 top-button"><i
            class="fa-solid fa-chevron-up"></i><span class="visually-hidden-focusable">To top</span></a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>