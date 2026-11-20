<?php

/**
 * Block Image
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Image
 */
if (!function_exists('bootscore_block_image_classes')) {
  /**
   * Replaces the core image block classes with Bootstrap's figure classes.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_image_classes($block_content, $block) {

    // Card images (card-img-top, card-img-bottom, card-img): Bootstrap expects the <img> itself as card child,
    // so print the image without the <figure> and move the card classes to it
    $card_image = bootscore_block_image_card($block_content);
    if ($card_image !== null) {
      return apply_filters('bootscore/block/image/content', $card_image, $block);
    }

    $tags = new WP_HTML_Tag_Processor($block_content);

    // <figure>: wp-block-image and size-* => figure
    if ($tags->next_tag(array('tag_name' => 'FIGURE', 'class_name' => 'wp-block-image'))) {
      $tags->remove_class('wp-block-image');

      foreach ($tags->class_list() as $class) {
        if (str_starts_with($class, 'size-')) {
          $tags->remove_class($class);
        }
      }

      $tags->add_class('figure');
    }

    // <img>: add Bootstrap classes, keep wp-image-{id} (WordPress uses it for srcset)
    if ($tags->next_tag(array('tag_name' => 'IMG'))) {
      $tags->add_class('img-fluid rounded');
    }

    // <figcaption>: wp-element-caption => figure-caption
    if ($tags->next_tag(array('tag_name' => 'FIGCAPTION', 'class_name' => 'wp-element-caption'))) {
      $tags->remove_class('wp-element-caption');
      $tags->add_class('figure-caption');
    }

    $block_content = $tags->get_updated_html();

    return apply_filters('bootscore/block/image/content', $block_content, $block);
  }
}
add_filter('render_block_core/image', 'bootscore_block_image_classes', 10, 2);


if (!function_exists('bootscore_block_image_card')) {
  /**
   * An image block with a card-img class as bare <img>
   *
   * @param string $block_content The block content.
   * @return string|null The <img> with the figure's classes, or null if it isn't a card image.
   */
  function bootscore_block_image_card($block_content) {

    $tags = new WP_HTML_Tag_Processor($block_content);

    if (!$tags->next_tag(array('tag_name' => 'FIGURE'))) {
      return null;
    }

    $classes = array();
    $is_card = false;

    foreach ($tags->class_list() as $class) {
      if (in_array($class, array('card-img-top', 'card-img-bottom', 'card-img'), true)) {
        $is_card = true;
      }
      // Keep the figure's own classes, drop WordPress' block classes
      if ($class !== 'wp-block-image' && !str_starts_with($class, 'size-')) {
        $classes[] = $class;
      }
    }

    if (!$is_card || !preg_match('/<img\b[^>]*>/i', $block_content, $match)) {
      return null;
    }

    $img = new WP_HTML_Tag_Processor($match[0]);
    $img->next_tag();
    foreach ($classes as $class) {
      $img->add_class($class);
    }

    return $img->get_updated_html();
  }
}
