# Loop templates

> [!IMPORTANT]
> This loop templates are not linked in the theme yet. Templates will be used in Bootscore v7 in `archive.php`, `search.php` and `index.php` instead an own loop in each file. There will be an option for more templates like vertical cards, heroes and a custom blank template. The reason why this files are already here is because bs Grid, bs Isotope and bs Swiper plugins will use this templates as well instead in each plugin shortcode and allows to develop this plugins right now.

### Call the loop

```php
<!-- Loop START -->  
<?php
// Set layout via filter (can be overridden by plugins)
$layout = apply_filters('bootscore/loop/layout', 'horizontal'); // or 'grid'
?>

<?php if (have_posts()) : ?>

  <?php if ($layout === 'grid') : ?>
    <!-- Grid layout needs row wrapper -->
    <div class="<?= esc_attr(apply_filters('bootscore/class/loop/grid/col', 'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4', 'index')); ?>">
  <?php endif; ?>

  <?php while (have_posts()) : the_post(); ?>

    <?php if ($layout === 'grid') : ?>
      <!-- Add col wrapper for grid layout -->
      <div class="col">
        <?php get_template_part('template-parts/loop/cards'); ?>
      </div><!-- .col -->
    <?php else : ?>
      <!-- Horizontal layout - no col wrapper -->
      <?php get_template_part('template-parts/loop/cards-horizontal'); ?>
    <?php endif; ?>

  <?php endwhile; ?>

  <?php if ($layout === 'grid') : ?>
    </div><!-- .row -->
  <?php endif; ?>

<?php else : ?>
  <!-- No posts found -->
  <?php get_template_part('template-parts/loop/loop-none'); ?>
<?php endif; ?>
<!-- Loop END -->
```

<details>
  <summary>archive.php</summary>

```php
<?php

/**
 * The template for displaying archive pages
 * Template Version: 7.0.0
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

            
              <!-- Loop START -->  
              <?php
              // Set layout via filter (can be overridden by plugins)
              $layout = apply_filters('bootscore/loop/layout', 'horizontal'); // or 'grid'
              ?>

              <?php if (have_posts()) : ?>

                <?php if ($layout === 'grid') : ?>
                  <!-- Grid layout needs row wrapper -->
                  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/grid/col', 'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4', 'index')); ?>">
                <?php endif; ?>

                <?php while (have_posts()) : the_post(); ?>

                  <?php if ($layout === 'grid') : ?>
                    <!-- Add col wrapper for grid layout -->
                    <div class="col">
                      <?php get_template_part('template-parts/loop/cards'); ?>
                    </div><!-- .col -->
                  <?php else : ?>
                    <!-- Horizontal layout - no col wrapper -->
                    <?php get_template_part('template-parts/loop/cards-horizontal'); ?>
                  <?php endif; ?>

                <?php endwhile; ?>

                <?php if ($layout === 'grid') : ?>
                  </div><!-- .row -->
                <?php endif; ?>

              <?php else : ?>
                <!-- No posts found -->
                <?php get_template_part('template-parts/loop/loop-none'); ?>
              <?php endif; ?>
              <!-- Loop END -->            
            
            
            
            
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

```php
<?php

/**
 * The main template file
 * Template Version: 7.0.0
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>
  <div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'index')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'index')); ?>">
      <div id="primary" class="content-area">

        <?php do_action('bootscore_after_primary_open', 'index'); ?>

        <main id="main" class="site-main">

          <!-- Header -->
          <div class="p-5 text-center bg-body-tertiary rounded mb-4">
            <?php do_action( 'bootscore_before_title', 'index' ); ?>
            <h1 class="entry-title <?= esc_attr(apply_filters('bootscore/class/entry/title', '', 'index')); ?>"><?= esc_html(get_bloginfo('name')); ?></h1>
            <?php do_action( 'bootscore_after_title', 'index' ); ?>
            <p class="lead mb-0"><?= esc_html(get_bloginfo('description')); ?></p>
          </div>

          <!-- Post List -->
          <div class="row">
            <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col')); ?>">
              
              <!-- Loop START -->  
              <?php
              // Set layout via filter (can be overridden by plugins)
              $layout = apply_filters('bootscore/loop/layout', 'horizontal'); // or 'grid'
              ?>

              <?php if (have_posts()) : ?>

                <?php if ($layout === 'grid') : ?>
                  <!-- Grid layout needs row wrapper -->
                  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/grid/col', 'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4', 'index')); ?>">
                <?php endif; ?>

                <?php while (have_posts()) : the_post(); ?>

                  <?php if ($layout === 'grid') : ?>
                    <!-- Add col wrapper for grid layout -->
                    <div class="col">
                      <?php get_template_part('template-parts/loop/cards'); ?>
                    </div><!-- .col -->
                  <?php else : ?>
                    <!-- Horizontal layout - no col wrapper -->
                    <?php get_template_part('template-parts/loop/cards-horizontal'); ?>
                  <?php endif; ?>

                <?php endwhile; ?>

                <?php if ($layout === 'grid') : ?>
                  </div><!-- .row -->
                <?php endif; ?>

              <?php else : ?>
                <!-- No posts found -->
                <?php get_template_part('template-parts/loop/loop-none'); ?>
              <?php endif; ?>
              <!-- Loop END -->
              
              <?php do_action('bootscore_after_loop', 'index'); ?>

              <div class="entry-footer">
                
                <?php do_action( 'bootscore_before_pagination', 'index' ); ?>
                
                <?php bootscore_pagination(); ?>
                
              </div>
              
            </div>
            <?php get_sidebar(); ?>
          </div>
      </main>
    </div>
  </div>
<?php
get_footer();
```

</details>

<details>
  <summary>search.php</summary>

```php
<?php

/**
 * The template for displaying search results pages
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();

// Get layout from filter (default to 'horizontal' for search)
$layout = apply_filters('bootscore/loop/layout', 'horizontal', 'search');
?>
  <div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'search')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'search')); ?>">
    <div id="primary" class="content-area">
      
      <?php do_action( 'bootscore_after_primary_open', 'search' ); ?>

      <div class="row">
        <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col')); ?>">

          <main id="main" class="site-main">

            <?php if (have_posts()) : ?>

              <div class="entry-header">
                <?php do_action( 'bootscore_before_title', 'search' ); ?>
                <h1 class="entry-title <?= esc_attr(apply_filters('bootscore/class/entry/title', '', 'search')); ?>">
                  <?php
                  /* translators: %s: search query. */
                  printf(esc_html__('Search Results for: %s', 'bootscore'), '<span class="text-body-secondary">' . esc_html(get_search_query()) . '</span>');
                  ?>
                </h1>
                <?php do_action( 'bootscore_after_title', 'search' ); ?>
              </div>
            
              <?php do_action( 'bootscore_before_loop', 'search' ); ?>

              <!-- Loop START -->
              <?php
              // Set layout via filter (can be overridden by plugins)
              $layout = apply_filters('bootscore/loop/layout', 'horizontal', 'search'); // or 'grid'
              ?>

              <?php if (have_posts()) : ?>

                <?php if ($layout === 'grid') : ?>
                  <!-- Grid layout needs row wrapper -->
                  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/grid/col', 'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4', 'search')); ?>">
                <?php endif; ?>

                <?php while (have_posts()) : the_post(); ?>

                  <?php if ($layout === 'grid') : ?>
                    <!-- Add col wrapper for grid layout -->
                    <div class="col">
                      <?php get_template_part('template-parts/loop/cards'); ?>
                    </div><!-- .col -->
                  <?php else : ?>
                    <!-- Horizontal layout - no col wrapper -->
                    <?php get_template_part('template-parts/loop/cards-horizontal'); ?>
                  <?php endif; ?>

                <?php endwhile; ?>

                <?php if ($layout === 'grid') : ?>
                  </div><!-- .row -->
                <?php endif; ?>

              <?php else : ?>
                <!-- No posts found -->
                <?php get_template_part('template-parts/loop/loop-none'); ?>
              <?php endif; ?>
              <!-- Loop END -->

              <?php do_action( 'bootscore_after_loop', 'search' ); ?>
              
              <?php do_action( 'bootscore_before_pagination', 'search' ); ?>

              <?php bootscore_pagination(); ?>

            <?php else : ?>

              <?php get_template_part('template-parts/loop/content', 'none'); ?>

            <?php endif; ?>
            
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
  <summary>Filters & Actions</summary>

#### New

- `bootscore/thumbnail/archive/class` (location) - template-tags.php
- `bootscore/thumbnail/archive/size` (location) - template-tags.php
- `bootscore/class/loop/grid/col` (location) - archive.php, index.php, search.php

##### Usage

```php
/**
 * Enable grid layout in loop
 */
add_filter('bootscore/loop/layout', function($layout) {
  return 'grid'; // Override default 'horizontal'
});


/**
 * Change loop grid layout item col
 */

function change_loop_grid_layout_columns() {
  return "row row-cols-2 row-cols-lg-3 g-3 mb-3";
}
add_filter('bootscore/class/loop/grid/col', 'change_loop_grid_layout_columns', 10, 2);
```

#### Deleted

- `bootscore/class/loop/card/title/link`
- `bootscore/class/loop/card-text/excerpt/link`

</details>

### To Do

- [ ] Move https://github.com/bootscore/bootscore/blob/main/assets/scss/bootscore/_loop.scss to deprecated
- [ ] Update loop thumbnail image script in `template-tags.php`

```php
/**
 * Featured image
 */
if (!function_exists('bootscore_post_thumbnail')) :
  /**
   * Displays an optional post thumbnail.
   *
   * @param string $context Context for filtering (e.g., 'cards-horizontal')
   */
  function bootscore_post_thumbnail($context = '') {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
      return;
    }

    if (is_singular()) :

      $class = apply_filters('bootscore/thumbnail/single/class', 'rounded mb-3', $context);
      ?>

      <div class="post-thumbnail">
        <?php the_post_thumbnail('full', array('class' => esc_attr($class))); ?>
      </div><!-- .post-thumbnail -->

    <?php else : ?>

      <?php
      $class = apply_filters('bootscore/thumbnail/archive/class', '', $context);
      $size = apply_filters('bootscore/thumbnail/archive/size', 'post-thumbnail', $context);

      the_post_thumbnail($size, array(
        'alt' => the_title_attribute(array('echo' => false)),
        'class' => esc_attr($class)
      ));
      ?>

    <?php
    endif;
  }
endif;
``` 
- [ ] Check `the_excerpt`
- [ ] Add templates
  - [x] `cards-horizontal.php`
  - [x] `cards.php`
    - [x] Revisit the `card-img-top` in vertical loops
  - [ ] `heroes.php`
  - [ ] `custom.php` blank template with an action hook
- [ ] Check for superfluous actions
- [ ] Check filter names
- [ ] Link templates in files
  - [ ] Call the loop via function `<?php bootscore_loop() ?>`?
  - [ ] `archive.php`
  - [ ] `index.php`
  - [ ] `search.php`
- [ ] Develop loop plugins
  - [ ] bs Grid > bs Loop?
  - [ ] bs Isotope
  - [ ] bs Swiper
- [ ] Delete folder `template-parts/search`  
- [ ] Documentation
- [ ] Delete this .md file

