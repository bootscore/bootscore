<?php
/**
 * Title: section heading subtitle
 * Slug: section-heading-subtitle
 * Categories: bootscore
 * https://developer.wordpress.org/themes/features/block-patterns/
 * 
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>
<!-- wp:group {"tagName":"section","className":"py-5 bg-body-tertiary","layout":{"type":"default"}} -->
<section class="wp-block-group py-5 bg-body-tertiary"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:heading {"className":"text-center"} -->
<h2 class="wp-block-heading text-center">Section with heading</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"lead text-center mb-4"} -->
<p class="lead text-center mb-4">This is a <code>section</code> with heading and subtitle wrapped in a <code>container</code>. Use it on the <code>page-blank</code> template.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Some content</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->