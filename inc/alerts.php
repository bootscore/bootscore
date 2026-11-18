<?php

/**
 * Theme alerts
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Alert icon per alert type (info, success, warning, danger), used in all theme alerts
 *
 * Change icons via filter, e.g.:
 * add_filter('bootscore/alert/icon', function ($icon, $type) {
 *   return 'danger' === $type ? 'circle-exclamation' : $icon;
 * }, 10, 2);
 *
 * @param string $type Alert type.
 * @return string Icon SVG, empty if the filter returns no icon.
 */
function bootscore_alert_icon($type = 'info') {
  $icons = array(
    'info'    => 'circle-info',
    'success' => 'circle-check',
    'warning' => 'triangle-exclamation',
    'danger'  => 'circle-exclamation',
  );

  $icon = apply_filters('bootscore/alert/icon', $icons[$type] ?? 'circle-info', $type);

  return $icon ? bootscore_icon($icon, false) : '';
}


/**
 * Alert content: icon and text in a span (.alert is flex, links in the text would become separate flex items)
 *
 * @param string $text Alert text, may contain HTML.
 * @param string $type Alert type.
 * @return string Alert inner HTML.
 */
function bootscore_alert_content($text, $type = 'info') {
  return bootscore_alert_icon($type) . '<span class="alert-text">' . trim($text) . '</span>';
}


/**
 * Must log in notice
 */
add_filter('comment_form_defaults', function ($defaults) {
  if (!preg_match('/^(\s*<p[^>]*>)(.*)(<\/p>\s*)$/s', $defaults['must_log_in'], $m)) {
    return $defaults;
  }

  $tags = new WP_HTML_Tag_Processor($m[1]);

  if ($tags->next_tag('p')) {
    $tags->add_class(apply_filters('bootscore/class/comment/must-log-in', 'alert theme-info'));
  }

  $defaults['must_log_in'] = $tags->get_updated_html() . bootscore_alert_content($m[2]) . $m[3];

  return $defaults;
});
