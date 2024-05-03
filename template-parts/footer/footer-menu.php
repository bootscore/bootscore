<?php

/**
 * Template part to initialize the footer menu
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<?php
// Bootstrap 5 Nav Walker
wp_nav_menu(array(
  'theme_location' => 'footer-menu',
  'container'      => false,
  'menu_class'     => '',
  'fallback_cb'    => '__return_false',
  'items_wrap'     => '<ul id="footer-menu" class="nav %2$s">%3$s</ul>',
  'depth'          => 1,
  'walker'         => new bootstrap_5_wp_nav_menu_walker()
));
?>