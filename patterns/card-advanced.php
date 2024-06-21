<?php
/**
 * Title: card advanced
 * Slug: bootscore/card-advanced
 * Categories: bootscore
 * https://developer.wordpress.org/themes/features/block-patterns/
 * 
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>
<!-- wp:group {"metadata":{"name":"card advanced"},"className":"card"} -->
<div class="wp-block-group card"><!-- wp:heading {"className":"card-header h6"} -->
<h2 class="wp-block-heading card-header h6">Card header</h2>
<!-- /wp:heading -->

<!-- wp:image {"sizeSlug":"large","className":"mb-0"} -->
<figure class="wp-block-image size-large mb-0"><img src="https://dummyimage.com/1200x900/6c757d/ffffff" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"level":3,"className":"card-title h5"} -->
<h3 class="wp-block-heading card-title h5">Card title</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"card-text"} -->
<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a class="btn btn-primary" href="#">Button</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"card-footer"} -->
<p class="card-footer">Card footer</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->