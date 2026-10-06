<?php

/**
 * Template part to initialize the navbar menu
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<?php
// Bootstrap 6 Nav Walker
wp_nav_menu(array(
  'theme_location'       => 'main-menu',
  'container'            => 'nav',
  'container_class'      => apply_filters('bootscore/class/header/navbar-nav', 'lg:ms-auto'),
  'container_aria_label' => __('Main menu', 'bootscore'),
  'menu_class'           => '',
  'fallback_cb'          => '__return_false',
  'items_wrap'           => '<ul id="bootscore-navbar" class="navbar-nav">%3$s</ul>',
  'depth'                => 0,
  'walker'               => new bootstrap_6_wp_nav_menu_walker()
));
?>
