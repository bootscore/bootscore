# Loop templates

> [!IMPORTANT]
> This loop templates are not linked in the theme yet. Templates will be used in Bootscore v7 in archive.php, search.php and index.php instead an own loop in each file. There will be an option for more templates like vertical cards, heroes and a custom blank template. The reason why this files are already here is because bs Grid, bs Isotope and bs Swiper plugins will use this templates as well instead in each plugin shortcode and allows to develop this plugins right now.

#### Call the loop

```php
<!-- Loop items  -->
<?php get_template_part('template-parts/loop/cards-horizontal'); ?>
```

<details>
  <summary>archive.php</summary>
```php
<?php

/**
 * The template for displaying archive pages
 * Template Version: 6.4.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'archive')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'archive')); ?>">
    <div id="primary" class="content-area">
      
      <?php do_action('bootscore_after_primary_open', 'archive'); ?>

      <div class="row">
        <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col')) ?>">

          <main id="main" class="site-main">

            <div class="entry-header">
              <?php do_action( 'bootscore_before_title', 'archive' ); ?>
              <?php the_archive_title('<h1 class="entry-title ' . esc_attr(apply_filters('bootscore/class/entry/title', '', 'archive')) . '">', '</h1>'); ?>
              <?php do_action( 'bootscore_after_title', 'archive' ); ?>
              <?php the_archive_description( '<div class="archive-description ' . esc_attr(apply_filters('bootscore/class/entry/archive-description', '')) . '">', '</div>' ); ?>
            </div>
            
            <?php do_action( 'bootscore_before_loop', 'archive' ); ?>

            <?php if (have_posts()) : ?>
              <?php while (have_posts()) : the_post(); ?>
            
              <!-- Loop items  -->
              <?php get_template_part('template-parts/loop/cards-horizontal'); ?>

              <?php endwhile; ?>
            <?php endif; ?>
            
            <?php do_action('bootscore_after_loop', 'archive'); ?>

            <div class="entry-footer">
      
              <?php do_action( 'bootscore_before_pagination', 'archive' ); ?>
              
              <?php bootscore_pagination(); ?>
              
            </div>

          </main>

        </div>
        <?php get_sidebar(); ?>
      </div>

    </div>
  </div>

<?php
get_footer();
  
  
```  

</details>

<details>
  <summary>index.php</summary>
text
</details>

<details>
  <summary>search.phps</summary>
text
</details>