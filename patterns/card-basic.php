<?php
/**
 * Title: card basic
 * Slug: bootscore/card-basic
 * Categories: bootscore
 * https://developer.wordpress.org/themes/features/block-patterns/
 * 
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>
<!-- wp:group {"metadata":{"name":"card basic"},"className":"card"} -->
<div class="wp-block-group card"><!-- wp:image {"sizeSlug":"large","className":"card-img-top mb-0"} -->
<figure class="wp-block-image size-large card-img-top mb-0"><img src="https://dummyimage.com/1200x900/6c757d/ffffff" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"className":"card-title h5"} -->
<h2 class="wp-block-heading card-title h5">Card title</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Some quick example text to build on the card title and make up the bulk of the card's content.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a class="btn btn-primary" href="#">Button</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->