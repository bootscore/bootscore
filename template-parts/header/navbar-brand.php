<?php

/**
 * Template part for displaying the navbar logo
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<!-- Navbar Brand -->
<a class="navbar-brand" href="<?= esc_url(home_url()); ?>">
  <img src="<?= esc_url(apply_filters('bootscore/logo', get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg', 'default')); ?>" alt="<?php bloginfo('name'); ?> Logo" class="d-td-none me-2">
  <img src="<?= esc_url(apply_filters('bootscore/logo', get_stylesheet_directory_uri() . '/assets/img/logo/logo-theme-dark.svg', 'theme-dark')); ?>" alt="<?php bloginfo('name'); ?> Logo" class="d-tl-none me-2">
</a>  
