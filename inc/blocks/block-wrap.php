<?php

/**
 * Bootscore wrapper block: a minimal semantic wrapper with tag, class and custom attributes.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


add_action( 'init', 'bs_wrapper_register_block' );

if ( ! function_exists( 'bs_wrapper_register_block' ) ) {
  function bs_wrapper_register_block() {
    // Skip if the standalone plugin (or anything else) already registered the block.
    if ( WP_Block_Type_Registry::get_instance()->is_registered( 'bootscore/wrapper' ) ) {
      return;
    }

    $script_path = get_template_directory() . '/assets/js/blocks/block-wrap.js';

    wp_register_script(
      'bs-wrapper-editor',
      get_template_directory_uri() . '/assets/js/blocks/block-wrap.js',
      array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-data', 'wp-plugins', 'wp-editor' ),
      file_exists( $script_path ) ? filemtime( $script_path ) : wp_get_theme()->get( 'Version' ),
      true
    );

    register_block_type( 'bootscore/wrapper', array(
      'editor_script'   => 'bs-wrapper-editor',
      'render_callback' => 'bs_wrapper_render',
      'supports'        => array(
        'className'       => false,
        'customClassName' => false,
      ),
      'attributes'      => array(
        'tagName'         => array(
          'type'    => 'string',
          'default' => 'div',
        ),
        'cssClass'        => array(
          'type'    => 'string',
          'default' => '',
        ),
        'extraAttributes' => array(
          'type'    => 'array',
          'default' => array(),
          'items'   => array(
            'type'       => 'object',
            'properties' => array(
              'key'   => array( 'type' => 'string' ),
              'value' => array( 'type' => 'string' ),
            ),
          ),
        ),
      ),
    ) );
  }
}

/**
 * Attribute names that may be output besides data-* and aria-*.
 * Extend with the 'bs_wrapper_allowed_attributes' filter.
 */
if ( ! function_exists( 'bs_wrapper_allowed_attributes' ) ) {
  function bs_wrapper_allowed_attributes() {
    $allowed = array(
      'id',
      'role',
      'tabindex',
      'title',
      'lang',
      'dir',
      'hidden',
      'inert',
      'open',
      'popover',
      'popovertarget',
      'popovertargetaction',
      'type',
      'name',
      'value',
      'disabled',
    );

    return apply_filters( 'bs_wrapper_allowed_attributes', $allowed );
  }
}

if ( ! function_exists( 'bs_wrapper_is_allowed_attribute' ) ) {
  function bs_wrapper_is_allowed_attribute( $name ) {
    // Never allow event handlers, even if a filter adds them.
    if ( strpos( $name, 'on' ) === 0 ) {
      return false;
    }

    if ( preg_match( '/^(data|aria)-[a-z0-9_.-]+$/', $name ) ) {
      return true;
    }

    return in_array( $name, bs_wrapper_allowed_attributes(), true );
  }
}

if ( ! function_exists( 'bs_wrapper_render' ) ) {
  function bs_wrapper_render( $attributes, $content ) {
    $allowed_tags = array( 'div', 'span', 'address', 'section', 'article', 'header', 'footer', 'aside', 'nav', 'figure', 'details', 'summary', 'dialog', 'button', 'ul', 'li' );
    $tag = ( isset( $attributes['tagName'] ) && in_array( $attributes['tagName'], $allowed_tags, true ) ) ? $attributes['tagName'] : 'div';

    // Attributes with no value, rendered as a bare name (e.g. <details open>).
    $boolean_attrs = array( 'open', 'hidden', 'inert', 'disabled' );

    // Passed to core, which merges and escapes class and id.
    $core_attrs = array();
    // Everything else is built here and escaped once.
    $extra_html = array();

    if ( ! empty( $attributes['cssClass'] ) ) {
      $core_attrs['class'] = $attributes['cssClass'];
    }

    $extra_attrs = isset( $attributes['extraAttributes'] ) && is_array( $attributes['extraAttributes'] ) ? $attributes['extraAttributes'] : array();

    foreach ( $extra_attrs as $attr ) {
      if ( ! is_array( $attr ) ) continue;

      $key   = isset( $attr['key'] ) ? strtolower( trim( $attr['key'] ) ) : '';
      $value = isset( $attr['value'] ) ? (string) $attr['value'] : '';

      if ( $key === '' || $key === 'class' ) continue;
      if ( ! bs_wrapper_is_allowed_attribute( $key ) ) continue;

      // id is merged by core.
      if ( $key === 'id' ) {
        if ( $value !== '' ) {
          $core_attrs['id'] = $value;
        }
        continue;
      }

      // Boolean attributes: bare name, unless explicitly turned off.
      if ( in_array( $key, $boolean_attrs, true ) ) {
        if ( $value === '' || $value === $key || $value === 'true' ) {
          $extra_html[ $key ] = $key;
        }
        continue;
      }

      // URL-type attributes (only reachable via the filter) get URL validation.
      if ( in_array( $key, array( 'href', 'src', 'action', 'formaction' ), true ) ) {
        $value = esc_url( $value );
      }

      $extra_html[ $key ] = sprintf( '%s="%s"', $key, esc_attr( $value ) );
    }

    $block_wrapper_attrs = get_block_wrapper_attributes( $core_attrs );

    if ( ! empty( $extra_html ) ) {
      $block_wrapper_attrs = trim( $block_wrapper_attrs . ' ' . implode( ' ', $extra_html ) );
    }

    return sprintf(
      '<%1$s %2$s>%3$s</%1$s>',
      esc_attr( $tag ),
      $block_wrapper_attrs,
      $content
    );
  }
}
