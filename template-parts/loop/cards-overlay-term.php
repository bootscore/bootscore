<?php

/**
 * Template part for displaying taxonomy TERM loop items in cards-overlay (hero template).
 * Counterpart to cards-overlay.php (posts), used by bs-loop when 'type=""'
 * is a taxonomy rather than a post type (e.g. type="category" layout="overlay").
 * Template Version: 6.5.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * CAUTION: this layout's card height comes entirely from the image
 * (.card-img-overlay is absolutely positioned). A term without a thumbnail
 * (e.g. plain "category"/"post_tag" with no thumbnail_id meta set) will render
 * with no image and the card can collapse to zero height. See README.
 *
 * Expects $args['term'] to be a WP_Term object (passed by bs-loop).
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

$context = 'cards-overlay';

$term = (!empty($args['term']) && $args['term'] instanceof WP_Term) ? $args['term'] : null;
if (!$term) {
  return;
}

$term_link = get_term_link($term);
$term_link = is_wp_error($term_link) ? '' : $term_link;

// Reuses the 'thumbnail_id' term meta key (the same one WooCommerce uses for
// product category images), so any plugin that sets a term thumbnail this way will show up here.
// Core WordPress categories/tags have no built-in term thumbnail, so $term_thumbnail_id
// will be empty for them by default. Unlike cards-horizontal/cards-grid, this layout's
// height comes entirely from the image — see the CAUTION note above the file header.
$term_thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);

$term_count_label = apply_filters(
  'bootscore/bs-loop/term/count-label',
  _n('item', 'items', $term->count, 'bootscore'),
  $term,
  'cards-overlay'
);
?>

<?php do_action( 'bootscore_before_loop_item', 'cards-overlay' ); ?>

<div id="term-<?= esc_attr($term->term_id); ?>" class="<?= esc_attr(apply_filters('bootscore/class/loop/card', 'card rounded-4 overflow-hidden', 'cards-overlay')); ?>">

  <?php do_action('bootscore_before_loop_thumbnail', 'cards-overlay'); ?>

  <?php if ($term_thumbnail_id) : ?>
    <?php if ($term_link) : ?><a href="<?= esc_url($term_link); ?>" aria-hidden="true" tabindex="-1"><?php endif; ?>
      <?= wp_get_attachment_image($term_thumbnail_id, 'full', false, array('class' => esc_attr(apply_filters('bootscore/class/loop/card/image', 'card-img', 'cards-overlay')))); ?>
    <?php if ($term_link) : ?></a><?php endif; ?>
  <?php endif; ?>

  <?php do_action('bootscore_after_loop_thumbnail', 'cards-overlay'); ?>

  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/img-overlay', 'card-img-overlay d-flex flex-column', 'cards-overlay')); ?>">

    <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/img-overlay/content/wrapper', 'bg-body bg-opacity-50 backdrop-blur-10 rounded-3 p-3 mt-auto', 'cards-overlay')); ?>">

      <?php do_action('bootscore_before_loop_title', 'cards-overlay'); ?>

      <?php if ($term_link) : ?>
        <a class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title/link', 'text-body text-decoration-none', 'cards-overlay')); ?>" href="<?= esc_url($term_link); ?>">
          <h2 class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title', 'h5', 'cards-overlay')); ?>"><?= esc_html($term->name); ?></h2>
        </a>
      <?php else : ?>
        <h2 class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title', 'h5', 'cards-overlay')); ?>"><?= esc_html($term->name); ?></h2>
      <?php endif; ?>

      <?php do_action('bootscore_after_loop_title', 'cards-overlay'); ?>

      <?php if (apply_filters('bootscore/loop/meta', true, 'cards-overlay') && $term->count > 0) : ?>
        <p class="meta small mb-2 text-body-secondary">
          <?= esc_html($term->count . ' ' . $term_count_label); ?>
        </p>
      <?php endif; ?>

      <?php if (apply_filters('bootscore/loop/excerpt', true, 'cards-overlay') && !empty($term->description)) : ?>
        <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/excerpt', 'card-text', 'cards-overlay')); ?>">
          <?= esc_html(wp_trim_words(wp_strip_all_tags($term->description), 20)); ?>
        </p>
      <?php endif; ?>

      <?php if (apply_filters('bootscore/loop/read-more', true, 'cards-overlay') && $term_link) : ?>
        <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/read-more', 'card-text mt-auto', 'cards-overlay')); ?>">
          <a class="<?= esc_attr(apply_filters('bootscore/class/loop/read-more', 'read-more', 'cards-overlay')); ?>" href="<?= esc_url($term_link); ?>">
            <?= wp_kses_post(apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore'), 'cards-overlay')); ?>
          </a>
        </p>
      <?php endif; ?>

      <?php do_action('bootscore_after_loop_tags', 'cards-overlay'); ?>

    </div>

  </div>

  <?php do_action('bootscore_loop_item_after_card_img_overlay', 'cards-overlay'); ?>

</div>

<?php do_action('bootscore_after_loop_item', 'cards-overlay'); ?>