<?php
/**
 * Template part for displaying custom loop items
 * Template Version: 6.4.0
 *
 * This template provides an action hook for developers to inject
 * completely custom loop item markup.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

$context = 'custom';
?>

<?php do_action( 'bootscore_before_loop_item', $context); ?>

<?php do_action('bootscore_custom_loop_item', $context); ?>

<?php do_action( 'bootscore_after_loop_item', $context); ?>
