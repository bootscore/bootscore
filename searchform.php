<?php

/**
 * Template to show classic searchform widget
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.1.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<form class="searchform input-group" method="get" action="<?= esc_url(home_url('/')); ?>" role="search">
  <input type="text" name="s" class="form-control" placeholder="<?php _e('Search', 'bootscore'); ?>">
  <button type="submit" class="input-group-text <?= apply_filters('bootscore/class/widget/search/button', 'btn btn-outline-secondary'); ?>" aria-label="<?php esc_attr_e( 'Submit search', 'bootscore' ); ?>"><?= apply_filters('bootscore/icon/search', '<i class="fa-solid fa-magnifying-glass"></i>'); ?> <span class="visually-hidden-focusable">Search</span></button>
</form>
