<?php

/**
 * Template part for displaying taxonomy TERM loop items in horizontal cards.
 * Counterpart to cards-horizontal.php (posts), used by bs-loop when 'type=""'
 * is a taxonomy rather than a post type (e.g. type="category").
 * Template Version: 6.5.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * The `overflow-hidden` class is used in cards to create responsive border radius in the image.
 * The `h-100` class is used to create equal height cards when changing the `row-cols-*`.
 *
 * Expects $args['term'] to be a WP_Term object (passed by bs-loop).
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

$context = 'cards-horizontal';

$term = (!empty($args['term']) && $args['term'] instanceof WP_Term) ? $args['term'] : null;
if (!$term) {
  return;
}

$term_link = get_term_link($term);
$term_link = is_wp_error($term_link) ? '' : $term_link;

// Reuses the 'thumbnail_id' term meta key (the same one WooCommerce uses for
// product category images), so any plugin that sets a term thumbnail this way will show up here.
// Core WordPress categories/tags have no built-in term thumbnail, so $term_thumbnail_id
// will be empty for them by default — the block below simply omits the image in that case,
// which is fine for this layout (card height comes from the body, not the image).
$term_thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);

$term_count_label = apply_filters(
  'bootscore/bs-loop/term/count-label',
  _n('item', 'items', $term->count, 'bootscore'),
  $term,
  'cards-horizontal'
);
?>

<?php do_action('bootscore_before_loop_item', 'cards-horizontal'); ?>

<div id="term-<?= esc_attr($term->term_id); ?>" class="<?= esc_attr(apply_filters('bootscore/class/loop/card', 'card horizontal overflow-hidden h-100', 'cards-horizontal')); ?>">

  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/row', 'row g-0 h-100 flex-column flex-md-row', 'cards-horizontal')); ?>">

    <?php do_action('bootscore_before_loop_thumbnail', 'cards-horizontal'); ?>

    <?php if ($term_thumbnail_id) : ?>
      <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/image/col', 'col-md-5 col-lg-6 col-xl-5 col-xxl-4', 'cards-horizontal')); ?>">
        <?php if ($term_link) : ?><a href="<?= esc_url($term_link); ?>" aria-hidden="true" tabindex="-1"><?php endif; ?>
          <?= wp_get_attachment_image($term_thumbnail_id, 'medium', false, array('class' => esc_attr(apply_filters('bootscore/class/loop/card/image', 'h-md-100 object-fit-md-cover', 'cards-horizontal')))); ?>
        <?php if ($term_link) : ?></a><?php endif; ?>
      </div>
    <?php endif; ?>

    <?php do_action('bootscore_after_loop_thumbnail', 'cards-horizontal'); ?>

    <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/col', 'col', 'cards-horizontal')); ?>">
      <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/body', 'card-body h-100 d-flex flex-column', 'cards-horizontal')); ?>">

        <?php do_action('bootscore_before_loop_title', 'cards-horizontal'); ?>

        <?php if ($term_link) : ?>
          <a class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title/link', 'text-body text-decoration-none', 'cards-horizontal')); ?>" href="<?= esc_url($term_link); ?>">
            <h2 class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title', 'h5', 'cards-horizontal')); ?>"><?= esc_html($term->name); ?></h2>
          </a>
        <?php else : ?>
          <h2 class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title', 'h5', 'cards-horizontal')); ?>"><?= esc_html($term->name); ?></h2>
        <?php endif; ?>

        <?php do_action('bootscore_after_loop_title', 'cards-horizontal'); ?>

        <?php if (apply_filters('bootscore/loop/meta', true, 'cards-horizontal') && $term->count > 0) : ?>
          <p class="meta small mb-2 text-body-secondary">
            <?= esc_html($term->count . ' ' . $term_count_label); ?>
          </p>
        <?php endif; ?>

        <?php if (apply_filters('bootscore/loop/excerpt', true, 'cards-horizontal') && !empty($term->description)) : ?>
          <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/excerpt', 'card-text', 'cards-horizontal')); ?>">
            <?= esc_html(wp_trim_words(wp_strip_all_tags($term->description), 20)); ?>
          </p>
        <?php endif; ?>

        <?php if (apply_filters('bootscore/loop/read-more', true, 'cards-horizontal') && $term_link) : ?>
          <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/read-more', 'card-text mt-auto', 'cards-horizontal')); ?>">
            <a class="<?= esc_attr(apply_filters('bootscore/class/loop/read-more', 'read-more', 'cards-horizontal')); ?>" href="<?= esc_url($term_link); ?>">
              <?= wp_kses_post(apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore'), 'cards-horizontal')); ?>
            </a>
          </p>
        <?php endif; ?>

        <?php do_action('bootscore_after_loop_tags', 'cards-horizontal'); ?>

      </div>

      <?php do_action('bootscore_loop_item_after_card_body', 'cards-horizontal'); ?>

    </div>
  </div>
</div>

<?php do_action('bootscore_after_loop_item', 'cards-horizontal'); ?>
