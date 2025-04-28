<?php
/**
 * Title: c - Card with header and footer
 * Slug: bootscore/c.card-header-footer
 * Categories: bootscore
 * https://developer.wordpress.org/themes/features/block-patterns/
 * 
 * @package Bootscore
 * @version 6.2.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>
<!-- wp:group {"metadata":{"name":"c - Card with header and footer - card mb-3 hide-wp-block-classes","categories":["bootscore"],"patternName":"bootscore/c.card-advanced"},"className":"card mb-3 hide-wp-block-classes"} -->
<div class="wp-block-group card mb-3 hide-wp-block-classes"><!-- wp:heading {"metadata":{"name":"card-header h6"},"className":"card-header h6"} -->
<h2 class="wp-block-heading card-header h6">Card header</h2>
<!-- /wp:heading -->

<!-- wp:image {"sizeSlug":"large","metadata":{"name":"mb-0"},"className":"mb-0"} -->
<figure class="wp-block-image size-large mb-0"><img src="https://dummyimage.com/1200x900/6c757d/ffffff" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"metadata":{"name":"card-body"},"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"level":3,"metadata":{"name":"card-title h5"},"className":"card-title h5"} -->
<h3 class="wp-block-heading card-title h5">Card title</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"metadata":{"name":"card-text"},"className":"card-text"} -->
<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"metadata":{"name":"card-text"},"className":"card-text"} -->
<p class="card-text"><a class="btn btn-primary" href="#">Button</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"metadata":{"name":"card-footer mb-0"},"className":"card-footer mb-0"} -->
<p class="card-footer mb-0">Card footer</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->