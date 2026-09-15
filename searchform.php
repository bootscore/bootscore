<?php

/**
 * Template to show classic searchform widget
 * Used is content-none.php as well
 * 
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<form class="searchform input-group" method="get" action="<?= esc_url(home_url('/')); ?>" role="search">
  <input type="text" name="s" class="form-control" placeholder="<?php esc_attr_e('Search', 'bootscore'); ?>" value="<?= esc_attr(get_search_query()); ?>">
  <button type="submit" class="input-group-text <?= esc_attr(apply_filters('bootscore/class/widget/search/button', 'btn btn-outline-secondary')); ?>" aria-label="<?php esc_attr_e( 'Submit search', 'bootscore' ); ?>"><?php bootscore_icon('search'); ?><span class="visually-hidden-focusable">Search</span></button>
</form>
