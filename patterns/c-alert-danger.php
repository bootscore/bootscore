<?php
/**
 * Title: c - Alert danger with icon and dismiss button
 * Slug: bootscore/c-alert-danger
 * Categories: bootscore
 *
 * @package Bootscore
 * @version 7.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>
<!-- wp:bootscore/wrapper {"cssClass":"alert theme-danger","metadata":{"name":"c - Alert danger - alert theme-danger","categories":["bootscore"],"patternName":"bootscore/c-alert-danger"}} -->
<!-- wp:html -->
<?php bootscore_icon('circle-exclamation'); ?>
<!-- /wp:html -->

<!-- wp:paragraph -->
<p>A dismissible danger alert with an icon and a <a href="#">link</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="<?php esc_attr_e('Close', 'bootscore'); ?>"></button>
<!-- /wp:html -->
<!-- /wp:bootscore/wrapper -->
