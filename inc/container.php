<?php

/**
 * Container
 *
 * @package Bootscore 
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Allow modifying the default bootstrap container class
 * @return string
 */
if (!function_exists('bootscore_container_class')) {
  function bootscore_container_class() {
    return "container";
  }
}
