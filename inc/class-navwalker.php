<?php

/**
 * Navwalker
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Register the navwalker
 */
if (!function_exists('bootscore_register_navwalker')) :
  function bootscore_register_navwalker() {

    // Based on https://github.com/AlexWebLab/bootstrap-5-wordpress-navbar-walker, updated for Bootstrap 6 Menu
    class bootstrap_6_wp_nav_menu_walker extends Walker_Nav_menu {
      private $current_item;

      // Menu item classes that become a data-bs-placement value on the submenu toggle
      private $menu_placement_values = [
        'menu-placement-bottom-start' => 'bottom-start',
        'menu-placement-bottom-end'   => 'bottom-end',
        'menu-placement-top-start'    => 'top-start',
        'menu-placement-top-end'      => 'top-end',
        'menu-placement-start'        => 'left-start',
        'menu-placement-end'          => 'right-start',
      ];

      function start_lvl(&$output, $depth = 0, $args = null) {
        $indent  = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output .= "\n$indent<ul class=\"menu$submenu depth_$depth\">\n";
      }

      function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $this->current_item = $item;

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;

        // WordPress adds .menu-item to every <li>, which now collides with Bootstrap 6 .menu-item styles
        $classes = array_diff($classes, ['menu-item']);

        $classes[] = 'nav-item';
        $classes[] = 'nav-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        // Filter again, nav_menu_css_class callbacks may re-add it
        $class_names = trim(preg_replace('/(^|\s)menu-item(\s|$)/', ' ', $class_names));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes  = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        // active_class start
        $classes = (array) $item->classes;

        /**
         * 1) Keep standard WordPress core signals (extended to include the canonical menu classes)
         */
        $is_core_current = (
            $item->current
            || $item->current_item_ancestor
            || in_array('current-post-ancestor', $classes, true)
            || in_array('current-menu-item', $classes, true)
            || in_array('current-menu-parent', $classes, true)
            || in_array('current-menu-ancestor', $classes, true)
        );

        /**
         * 2) Keep CPT archive menu items active on CPT singles as well (generic, no hardcoding)
         */
        $is_cpt_active = (
            isset($item->type)
            && $item->type === 'post_type_archive'
            && !empty($item->object)
            && (is_post_type_archive($item->object) || is_singular($item->object))
        );

        /**
         * 3) Only evaluate current_page_parent where it is semantically reliable
         */
        $page_for_posts  = (int) get_option('page_for_posts');
        $is_posts_page   = (!empty($item->object_id) && (int) $item->object_id === $page_for_posts);
        $is_blog_context = (is_home() || is_singular('post') || is_category() || is_tag() || is_date() || is_author());

        $is_safe_page_parent = (
            in_array('current_page_parent', $classes, true)
            && (
                is_page()
                || ($is_posts_page && $is_blog_context)
            )
        );

        /**
         * 4) WooCommerce-specific handling
         */
        $is_wc_active = false;
        if (class_exists('WooCommerce')) {
            $shop_id       = (int) wc_get_page_id('shop');
            $is_shop_page  = (!empty($item->object_id) && (int) $item->object_id === $shop_id);
            $is_wc_context = (is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_singular('product'));

            $is_wc_shop_active = ($is_shop_page && $is_wc_context);

            $is_wc_term = (
                in_array($item->object, ['product_cat', 'product_tag'], true)
                && !empty($item->object_id)
            );
            $is_wc_term_active = false;
            if ($is_wc_term && (is_product_category() || is_product_tag())) {
                $queried = get_queried_object();
                $is_wc_term_active = ($queried && !empty($queried->term_id) && (int) $queried->term_id === (int) $item->object_id);
            }

            $is_wc_active = ($is_wc_shop_active || $is_wc_term_active);
        }

        $is_active    = ($is_core_current || $is_cpt_active || $is_safe_page_parent || $is_wc_active);
        $active_class = $is_active ? ' active' : '';
        // active_class end

        if ($item->current) {
          $attributes .= ' aria-current="page"';
        }

        $link_class = ($depth > 0) ? 'menu-item' : 'nav-link';

        if ($args->walker->has_children) {
          $attributes .= ' class="' . $link_class . $active_class . '" data-bs-toggle="menu" aria-expanded="false"';

          // Optional placement via menu item CSS class, e.g. menu-placement-bottom-end
          foreach ($item->classes as $class) {
            if (isset($this->menu_placement_values[$class])) {
              $attributes .= ' data-bs-placement="' . esc_attr($this->menu_placement_values[$class]) . '"';
              break;
            }
          }
        } else {
          $attributes .= ' class="' . $link_class . $active_class . '"';
        }

        $item_output  = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . wp_kses(apply_filters('the_title', $item->title, $item->ID), bootscore_kses_allowed_svg(wp_kses_allowed_html('post'))) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
      }
    }

  }
endif;
add_action('after_setup_theme', 'bootscore_register_navwalker');
