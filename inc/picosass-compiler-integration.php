<?php

//// PICOSASS JS INTEGRATION FOR BOOTSCORE ////
// Ported from picostrap5's inc/picosass-compiler-integration.php.
// Compiles bootscore.scss client-side using real Dart Sass (loaded from
// a CDN in the admin's browser), then AJAX-saves the result to
// assets/css/bootscore.min.css.

defined('ABSPATH') || exit;

// Where Bootscore's own SCSS lives, with child-theme fallback support
// (matches the `shouldProcessChild()` logic in your existing scss-compiler.php)
function bootscore_picosass_scss_dir() {
  return (is_child_theme() && bootscore_child_has_scss())
    ? get_stylesheet_directory() . '/assets/scss/'
    : get_template_directory() . '/assets/scss/';
}

function bootscore_picosass_scss_uri() {
  return (is_child_theme() && bootscore_child_has_scss())
    ? get_stylesheet_directory_uri() . '/assets/scss/'
    : get_template_directory_uri() . '/assets/scss/';
}

// Raw SCSS source fed into the <template id="the-scss"> element.
// No customizer variable injection needed (Bootscore has none) -
// just the real bootscore.scss content, verbatim.
function bootscore_get_main_sass() {
  $entry_file = bootscore_picosass_scss_dir() . 'bootscore.scss';
  $sass = file_exists($entry_file) ? file_get_contents($entry_file) : '';
  return apply_filters('bootscore/picosass/main_sass', $sass);
}

// ADD SCRIPT + SCSS SOURCE TO <head> - admin only, only when triggered
add_action('wp_head', function () {
  if (!current_user_can('administrator')) return;
  if (!isset($_GET['compile_sass'])) return;
  ?>
    <script type="module" src="<?php echo get_template_directory_uri() ?>/inc/picosass/picosass.js"></script>

    <template id="the-scss" class="prevent-autocompile" baseurl="<?php echo bootscore_picosass_scss_uri() ?>"
      <?php if (is_child_theme()): ?> fallback_baseurl="<?php echo get_template_directory_uri() . '/assets/scss/' ?>" <?php endif ?> >
      <?php echo bootscore_get_main_sass() ?>
    </template>
  <?php
});

// CHECK ONLINE CONNECTION (compiler needs the CDN)
add_action('wp_footer', function () {
  if (!current_user_can('administrator')) return;
  if (!isset($_GET['compile_sass'])) return;
  ?>
    <script>
      if (!navigator.onLine) { alert("You need to be online to use the SCSS compiler (it loads Dart Sass from a CDN)."); throw new Error("No network"); }
    </script>
  <?php
});

// RUN THE COMPILER + SAVE RESULT VIA AJAX
add_action('wp_footer', function () {
  if (!current_user_can('administrator')) return;
  if (!isset($_GET['compile_sass'])) return;
  ?>
    <script>
      let lastCssBundle = '';

      function bootscoreCompilingFinished(compiled) {
        if (lastCssBundle !== compiled.css) {
          const formdata = new FormData();
          formdata.append("nonce", "<?php echo wp_create_nonce("bootscore_save_css_bundle") ?>");
          formdata.append("action", "bootscore_save_css_bundle");
          formdata.append("css", compiled.css);
          formdata.append("sourceMap", compiled.sourceMap ? JSON.stringify(compiled.sourceMap) : "");
          fetch("<?php echo admin_url('admin-ajax.php') ?>", {
            method: "POST",
            credentials: "same-origin",
            headers: { "Cache-Control": "no-cache" },
            body: formdata
          }).then(r => r.text()).then(r => console.log("Saved: " + r))
            .catch(err => console.log("bootscore_save_css_bundle error: " + err));

          lastCssBundle = compiled.css;
        }

        <?php if (isset($_GET['autorecompile'])) { ?>
        setTimeout(function () {
          window.Picosass.Compile({}, bootscoreCompilingFinished);
        }, 7000);
        <?php } else { ?>
        setTimeout(function () {
          const url = new URL(window.location.href);
          url.search = "";
          window.location.href = url.href;
        }, 3000);
        <?php } ?>
      }

      window.addEventListener("DOMContentLoaded", () => {
        window.Picosass.Compile({}, bootscoreCompilingFinished);
      });
    </script>
  <?php
});

// AJAX HANDLER: SAVE COMPILED CSS TO assets/css/bootscore.min.css
add_action('wp_ajax_bootscore_save_css_bundle', function () {
  if (!is_user_logged_in() || !current_user_can('administrator')) return;

  check_ajax_referer('bootscore_save_css_bundle', 'nonce');

  $compiled_css = stripslashes($_POST['css']);

  if (isset($_POST['sourceMap']) && $_POST['sourceMap'] !== "") {
    if (apply_filters('bootscore/picosass/enable_sourcemap_comment', true)) {
      $compiled_css .= "\n/*# sourceMappingURL=bootscore.min.css.map */";
    }
  }

  global $wp_filesystem;
  if (empty($wp_filesystem)) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    WP_Filesystem();
  }

  $css_dir  = bootscore_picosass_scss_dir(); // reuse same child/parent logic
  $css_dir  = str_replace('/assets/scss/', '/assets/css/', $css_dir);
  $css_file = $css_dir . 'bootscore.min.css';

  if (!file_exists($css_dir)) {
    wp_mkdir_p($css_dir);
  }

  $saved = $wp_filesystem->put_contents($css_file, $compiled_css, FS_CHMOD_FILE);

  if ($saved && isset($_POST['sourceMap']) && $_POST['sourceMap'] !== "") {
    $wp_filesystem->put_contents($css_file . '.map', stripslashes($_POST['sourceMap']), FS_CHMOD_FILE);
  }

  echo $saved ? "New CSS bundle successfully saved." : "Error writing CSS file.";

  wp_die();
});

// ADMIN BAR TRIGGER
add_action('admin_bar_menu', function ($admin_bar) {
  if (!current_user_can('administrator')) return;

  $base_args = array('compile_sass' => '1', 'sass_nocache' => '1');

  if (!isset($_GET['autorecompile'])) {
    $admin_bar->add_node(array(
      'id'    => 'bootscore-recompile-sass',
      'title' => __('SCSS Compiler', 'bootscore'),
      'href'  => add_query_arg($base_args),
    ));
    $admin_bar->add_node(array(
      'id'     => 'bootscore-recompile-sass-once',
      'parent' => 'bootscore-recompile-sass',
      'title'  => __('Recompile Once', 'bootscore'),
      'href'   => add_query_arg($base_args),
    ));
    $admin_bar->add_node(array(
      'id'     => 'bootscore-recompile-sass-auto',
      'parent' => 'bootscore-recompile-sass',
      'title'  => __('Recompile Continuously', 'bootscore'),
      'href'   => add_query_arg(array_merge($base_args, array('autorecompile' => '1'))),
    ));
  } else {
    $admin_bar->add_node(array(
      'id'    => 'bootscore-recompile-sass',
      'title' => __('Stop SCSS Compiler', 'bootscore'),
      'href'  => add_query_arg(array('compile_sass' => false, 'sass_nocache' => false, 'autorecompile' => false)),
    ));
  }
}, 100);