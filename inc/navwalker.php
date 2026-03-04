<?php

/**
 * Navwalker
 *
 * @package Bootscore
 * @version 6.4.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Register the navwalker
 */
if (!function_exists('bootscore_register_navwalker')) :
  function bootscore_register_navwalker() {

    // https://github.com/AlexWebLab/bootstrap-5-wordpress-navbar-walker
    class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu {
      private $current_item;
      private $dropdown_menu_alignment_values = [
        'dropdown-menu-start',
        'dropdown-menu-end',
        'dropdown-menu-sm-start',
        'dropdown-menu-sm-end',
        'dropdown-menu-md-start',
        'dropdown-menu-md-end',
        'dropdown-menu-lg-start',
        'dropdown-menu-lg-end',
        'dropdown-menu-xl-start',
        'dropdown-menu-xl-end',
        'dropdown-menu-xxl-start',
        'dropdown-menu-xxl-end'
      ];

      function start_lvl(&$output, $depth = 0, $args = null) {
        $dropdown_menu_class[] = '';
        foreach($this->current_item->classes as $class) {
          if(in_array($class, $this->dropdown_menu_alignment_values)) {
            $dropdown_menu_class[] = $class;
          }
        }
        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
      }

      function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $this->current_item = $item;

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $li_attributes = '';
        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
        $classes[] = 'nav-item';
        $classes[] = 'nav-item-' . $item->ID;
        if ($depth && $args->walker->has_children) {
          $classes[] = 'dropdown-menu dropdown-menu-end';
        }

        $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
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
         *    menu-item-type-post_type_archive => $item->type === 'post_type_archive'
         *    The post type slug is stored in $item->object
         */
        $is_cpt_active = (
            isset($item->type) 
            && $item->type === 'post_type_archive' 
            && !empty($item->object)
            && (is_post_type_archive($item->object) || is_singular($item->object))
        );

        /**
         * 3) Do NOT evaluate current_page_parent globally; only where it is semantically reliable:
         *    - real page hierarchy (page context)
         *    - the Posts page (page_for_posts) only in blog context (not on CPT archives/singles)
         */
        $page_for_posts = (int) get_option('page_for_posts');
        $is_posts_page = (!empty($item->object_id) && (int) $item->object_id === $page_for_posts);
        $is_blog_context = (is_home() || is_singular('post') || is_category() || is_tag() || is_date() || is_author());

        $is_safe_page_parent = (
            in_array('current_page_parent', $classes, true)
            && (
                is_page() // Real page hierarchy
                || ($is_posts_page && $is_blog_context) // Posts page only in blog context
            )
        );

        /**
         * 4) WooCommerce-specific handling
         *    - Keep Shop page active in WooCommerce context (products, categories, tags, single products)
         *    - Keep product category/tag menu items active on their own archive pages
         */
        $is_wc_active = false;
        if (class_exists('WooCommerce')) {
            $shop_id = (int) wc_get_page_id('shop');
            $is_shop_page = (!empty($item->object_id) && (int) $item->object_id === $shop_id);
            $is_wc_context = (is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_singular('product'));
            
            // Shop page active in WooCommerce context
            $is_wc_shop_active = ($is_shop_page && $is_wc_context);
            
            // WooCommerce taxonomy menu items (categories/tags)
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

        $active_class = (
            $is_core_current
            || $is_cpt_active
            || $is_safe_page_parent
            || $is_wc_active
        ) ? 'active' : '';
        // active_class end

        $nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
        $attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . wp_kses_post(apply_filters('the_title', $item->title, $item->ID)) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
      }
    }

  }
endif;
add_action('after_setup_theme', 'bootscore_register_navwalker');
