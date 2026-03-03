# Loop templates

> [!IMPORTANT]
> This loop templates are not linked in the theme yet. Templates will be used in Bootscore v7 in `archive.php`, `search.php` and `index.php` instead an own loop in each file. There will be an option for more templates like vertical cards, heroes and a custom blank template. The reason why this files are already here is because bs Grid, bs Isotope and bs Swiper plugins will use this templates as well instead in each plugin shortcode and allows to develop this plugins right now.

### Call the loop

```php
<!-- Loop START -->
<?php
// Set layout via filter (can be overridden by plugins)
$layout = apply_filters('bootscore/loop/layout', 'horizontal', 'index'); // or 'grid'

// Default grid classes
$grid_classes = apply_filters('bootscore/class/loop/grid/col',
  'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4',
  'index'
);

// Default horizontal classes
$horizontal_classes = apply_filters('bootscore/class/loop/horizontal/col',
  'row row-cols-1 g-4 mb-4',
  'index'
);
?>

<?php if (have_posts()) : ?>

  <!-- Loop row wrapper -->
  <div class="<?= $layout === 'grid' ? esc_attr($grid_classes) : esc_attr($horizontal_classes); ?>">

    <?php while (have_posts()) : the_post(); ?>

      <!-- Column wrapper for ALL layouts -->
      <div class="col">

        <?php if ($layout === 'grid') : ?>
          <!-- Grid card -->
          <?php get_template_part('template-parts/loop/cards'); ?>
        <?php else : ?>
          <!-- Horizontal card -->
          <?php get_template_part('template-parts/loop/cards-horizontal'); ?>
        <?php endif; ?>

      </div><!-- .col -->

    <?php endwhile; ?>

  </div><!-- .row (loop row) -->

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
      <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col', 'archive')); ?>">

        <main id="main" class="site-main">

          <div class="entry-header">
            <?php do_action('bootscore_before_title', 'archive'); ?>
            <?php the_archive_title('<h1 class="entry-title ' . esc_attr(apply_filters('bootscore/class/entry/title', '', 'archive')) . '">', '</h1>'); ?>
            <?php do_action('bootscore_after_title', 'archive'); ?>
            <?php the_archive_description('<div class="archive-description ' . esc_attr(apply_filters('bootscore/class/entry/archive-description', '')) . '">', '</div>'); ?>
          </div>
          
          <?php do_action('bootscore_before_loop', 'archive'); ?>

          <!-- Loop START -->
          <?php
          // Set layout via filter (can be overridden by plugins)
          $layout = apply_filters('bootscore/loop/layout', 'horizontal', 'archive'); // or 'grid'

          // Default grid classes - context changed to 'archive'
          $grid_classes = apply_filters('bootscore/class/loop/grid/col',
            'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4',
            'archive' // Changed from 'index' to 'archive'
          );

          // Default horizontal classes - context changed to 'archive'
          $horizontal_classes = apply_filters('bootscore/class/loop/horizontal/col',
            'row row-cols-1 g-4 mb-4',
            'archive' // Changed from 'index' to 'archive'
          );
          ?>

          <?php if (have_posts()) : ?>

            <!-- Loop row wrapper -->
            <div class="<?= $layout === 'grid' ? esc_attr($grid_classes) : esc_attr($horizontal_classes); ?>">

              <?php while (have_posts()) : the_post(); ?>

                <!-- Column wrapper for ALL layouts -->
                <div class="col">

                  <?php if ($layout === 'grid') : ?>
                    <!-- Grid card -->
                    <?php get_template_part('template-parts/loop/cards'); ?>
                  <?php else : ?>
                    <!-- Horizontal card -->
                    <?php get_template_part('template-parts/loop/cards-horizontal'); ?>
                  <?php endif; ?>

                </div><!-- .col -->

              <?php endwhile; ?>

            </div><!-- .row (loop row) -->

          <?php else : ?>
            <!-- No posts found -->
            <?php get_template_part('template-parts/loop/loop-none'); ?>
          <?php endif; ?>
          <!-- Loop END -->            

          <?php do_action('bootscore_after_loop', 'archive'); ?>

          <div class="entry-footer">
    
            <?php do_action('bootscore_before_pagination', 'archive'); ?>
            
            <?php bootscore_pagination(); ?>
            
          </div>

        </main>

      </div><!-- .col -->
      <?php get_sidebar(); ?>
    </div><!-- .row -->

  </div><!-- #primary -->
</div><!-- #content -->

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
        <?php do_action('bootscore_before_title', 'index'); ?>
        <h1 class="entry-title <?= esc_attr(apply_filters('bootscore/class/entry/title', '', 'index')); ?>"><?= esc_html(get_bloginfo('name')); ?></h1>
        <?php do_action('bootscore_after_title', 'index'); ?>
        <p class="lead mb-0"><?= esc_html(get_bloginfo('description')); ?></p>
      </div>

      <!-- Main content row with sidebar -->
      <div class="row">
        <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col', 'index')); ?>">

          <!-- Loop START -->
          <?php
          // Set layout via filter (can be overridden by plugins)
          $layout = apply_filters('bootscore/loop/layout', 'horizontal', 'index'); // or 'grid'

          // Default grid classes
          $grid_classes = apply_filters('bootscore/class/loop/grid/col',
            'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4',
            'index'
          );

          // Default horizontal classes
          $horizontal_classes = apply_filters('bootscore/class/loop/horizontal/col',
            'row row-cols-1 g-4 mb-4',
            'index'
          );
          ?>

          <?php if (have_posts()) : ?>

            <!-- Loop row wrapper -->
            <div class="<?= $layout === 'grid' ? esc_attr($grid_classes) : esc_attr($horizontal_classes); ?>">

              <?php while (have_posts()) : the_post(); ?>

                <!-- Column wrapper for ALL layouts -->
                <div class="col">

                  <?php if ($layout === 'grid') : ?>
                    <!-- Grid card -->
                    <?php get_template_part('template-parts/loop/cards'); ?>
                  <?php else : ?>
                    <!-- Horizontal card -->
                    <?php get_template_part('template-parts/loop/cards-horizontal'); ?>
                  <?php endif; ?>

                </div><!-- .col -->

              <?php endwhile; ?>

            </div><!-- .row (loop row) -->

          <?php else : ?>
            <!-- No posts found -->
            <?php get_template_part('template-parts/loop/loop-none'); ?>
          <?php endif; ?>
          <!-- Loop END -->

          <?php do_action('bootscore_after_loop', 'index'); ?>

          <div class="entry-footer">
            <?php do_action('bootscore_before_pagination', 'index'); ?>
            <?php bootscore_pagination(); ?>
          </div>

        </div><!-- .col (main content) -->
        
        <?php get_sidebar(); ?>
      </div><!-- .row (main content row) -->

    </main><!-- #main -->

  </div><!-- #primary -->
</div><!-- #content -->

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
?>
<div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'search')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'search')); ?>">
  <div id="primary" class="content-area">
    
    <?php do_action('bootscore_after_primary_open', 'search'); ?>

    <div class="row">
      <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col', 'search')); ?>">

        <main id="main" class="site-main">

          <?php if (have_posts()) : ?>

            <div class="entry-header">
              <?php do_action('bootscore_before_title', 'search'); ?>
              <h1 class="entry-title <?= esc_attr(apply_filters('bootscore/class/entry/title', '', 'search')); ?>">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'bootscore'), '<span class="text-body-secondary">' . esc_html(get_search_query()) . '</span>');
                ?>
              </h1>
              <?php do_action('bootscore_after_title', 'search'); ?>
            </div>
          
            <?php do_action('bootscore_before_loop', 'search'); ?>

            <!-- Loop START -->
            <?php
            // Set layout via filter (can be overridden by plugins)
            $layout = apply_filters('bootscore/loop/layout', 'horizontal', 'search'); // or 'grid'

            // Default grid classes
            $grid_classes = apply_filters('bootscore/class/loop/grid/col',
              'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4',
              'search'
            );

            // Default horizontal classes
            $horizontal_classes = apply_filters('bootscore/class/loop/horizontal/col',
              'row row-cols-1 g-4 mb-4',
              'search'
            );
            ?>

            <?php if (have_posts()) : ?>

              <!-- Loop row wrapper -->
              <div class="<?= $layout === 'grid' ? esc_attr($grid_classes) : esc_attr($horizontal_classes); ?>">

                <?php while (have_posts()) : the_post(); ?>

                  <!-- Column wrapper for ALL layouts -->
                  <div class="col">

                    <?php if ($layout === 'grid') : ?>
                      <!-- Grid card -->
                      <?php get_template_part('template-parts/loop/cards'); ?>
                    <?php else : ?>
                      <!-- Horizontal card -->
                      <?php get_template_part('template-parts/loop/cards-horizontal'); ?>
                    <?php endif; ?>

                  </div><!-- .col -->

                <?php endwhile; ?>

              </div><!-- .row (loop row) -->

            <?php else : ?>
              <!-- No posts found -->
              <?php get_template_part('template-parts/loop/loop-none'); ?>
            <?php endif; ?>
            <!-- Loop END -->

            <?php do_action('bootscore_after_loop', 'search'); ?>
            
            <?php do_action('bootscore_before_pagination', 'search'); ?>

            <?php bootscore_pagination(); ?>

          <?php else : ?>

            <?php get_template_part('template-parts/loop/content', 'none'); ?>

          <?php endif; ?>
          
        </main>

      </div><!-- .col -->
      <?php get_sidebar(); ?>
    </div><!-- .row -->

  </div><!-- #primary -->
</div><!-- #content -->

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

```php
/**
 * Customize horizontal grid classes in context
 */
add_filter('bootscore/class/loop/horizontal/col', function($classes, $context) {
    if ($context === 'archive') {
        // 2 columns on tablet, 3 on desktop
        return 'row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4 bg-danger';
    }
    if ($context === 'index') {
        // Keep default for blog page
        return 'row row-cols-2 g-4 mb-4 bg-success';
    }
    if ($context === 'search') {
        // Keep default for blog page
        return 'row row-cols-2 g-4 mb-4 bg-info';
    }
    return $classes;
}, 10, 2);
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

