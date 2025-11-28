<?php

/**
 * Template to show classic searchform widget
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.3.1
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<form class="searchform input-group" method="get" action="<?= esc_url(home_url('/')); ?>" role="search">
  <input type="text" name="s" class="form-control" placeholder="<?php esc_attr_e('Search', 'bootscore'); ?>" value="<?= esc_attr(get_search_query()); ?>">
  <button type="submit" class="input-group-text <?= esc_attr(apply_filters('bootscore/class/widget/search/button', 'btn btn-outline-secondary')); ?>" aria-label="<?php esc_attr_e( 'Submit search', 'bootscore' ); ?>"><?= wp_kses_post(apply_filters('bootscore/icon/search', '<i class="fa-solid fa-magnifying-glass"></i>')); ?> <span class="visually-hidden-focusable">Search</span></button>
</form>
