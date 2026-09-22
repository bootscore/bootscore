<?php

/**
 * Class with functions to compile TypeScript files.
 *
 * ESBUILD-WASM JS INTEGRATION FOR BOOTSCORE
 * Parallel to inc/scss-compiler.php's PicoSASS integration.
 * Compiles assets/ts/index.ts client-side using esbuild-wasm (loaded from
 * a CDN in the admin's browser), then AJAX-saves the result to
 * assets/js/bootscore.min.js.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Check if the active child theme has its own assets/ts/index.ts.
 * Used to decide whether the compiler's baseurl/fallback_baseurl should
 * point at the child theme (with the parent as fallback) or just the
 * parent theme directly.
 *
 * Mirrors bootscore_child_has_scss() in scss-compiler.php.
 */
function bootscore_child_has_ts() {
  return file_exists(get_stylesheet_directory() . '/assets/ts/index.ts');
}


// Where Bootscore's own TS source lives, with child-theme fallback support
function bootscore_ts_compiler_dir() {
  return (is_child_theme() && bootscore_child_has_ts())
    ? get_stylesheet_directory() . '/assets/ts/'
    : get_template_directory() . '/assets/ts/';
}

function bootscore_ts_compiler_uri() {
  return (is_child_theme() && bootscore_child_has_ts())
    ? get_stylesheet_directory_uri() . '/assets/ts/'
    : get_template_directory_uri() . '/assets/ts/';
}

// Where the compiled JS ends up - reuses the same child/parent logic as
// the TS dir, just swapping the folder name.
function bootscore_ts_compiler_js_file() {
  $js_dir = str_replace('/assets/ts/', '/assets/js/', bootscore_ts_compiler_dir());
  return $js_dir . 'bootscore.min.js';
}

// TESTING: should the compiler run on this page load?
// True if explicitly requested via ?compile_ts=1, OR if an admin is
// viewing the frontend and bootscore.min.js doesn't exist yet.
function bootscore_ts_compiler_should_compile() {
  if (!current_user_can('administrator')) return false;
  if (isset($_GET['compile_ts'])) return true;
  return !file_exists(bootscore_ts_compiler_js_file());
}

// Raw TS source fed into the <template id="the-ts"> element.
// Just the real index.ts content, verbatim - esbuild-wasm's resolver
// plugin fetches everything it imports from there.
function bootscore_get_main_ts() {
  $entry_file = bootscore_ts_compiler_dir() . 'index.ts';
  $ts = file_exists($entry_file) ? file_get_contents($entry_file) : '';
  return apply_filters('bootscore/compiler/main_ts', $ts);
}


// ADD SCRIPT + TS SOURCE TO <head> - admin only, only when triggered
add_action('wp_head', function () {
  if (!bootscore_ts_compiler_should_compile()) return;
  ?>
    <script type="module" src="<?php echo get_template_directory_uri() ?>/assets/js/ts-compiler/ts-compiler.js"></script>

    <template id="the-ts" class="prevent-autocompile" baseurl="<?php echo bootscore_ts_compiler_uri() ?>"
      <?php if (is_child_theme()): ?> fallback_baseurl="<?php echo get_template_directory_uri() . '/assets/ts/' ?>" <?php endif ?> >
      <?php echo bootscore_get_main_ts() ?>
    </template>
  <?php
});

// CHECK ONLINE CONNECTION (compiler needs the CDN, for the esbuild-wasm binary)
add_action('wp_footer', function () {
  if (!bootscore_ts_compiler_should_compile()) return;
  ?>
    <script>
      if (!navigator.onLine) { alert("You need to be online to use the TS compiler (it loads esbuild-wasm from a CDN)."); throw new Error("No network"); }
    </script>
  <?php
});

// RUN THE COMPILER + SAVE RESULT VIA AJAX
add_action('wp_footer', function () {
  if (!bootscore_ts_compiler_should_compile()) return;
  ?>
    <script>
      let lastJsBundle = '';

      function bootscoreCompilingFinishedJs(compiled) {
        if (lastJsBundle !== compiled.js) {
          const formdata = new FormData();
          formdata.append("nonce", "<?php echo wp_create_nonce("bootscore_save_js_bundle") ?>");
          formdata.append("action", "bootscore_save_js_bundle");
          formdata.append("js", compiled.js);
          formdata.append("sourceMap", compiled.sourceMap ? JSON.stringify(compiled.sourceMap) : "");
          fetch("<?php echo admin_url('admin-ajax.php') ?>", {
            method: "POST",
            credentials: "same-origin",
            headers: { "Cache-Control": "no-cache" },
            body: formdata
          }).then(r => r.text()).then(r => console.log("Saved: " + r))
            .catch(err => console.log("bootscore_save_js_bundle error: " + err));

          lastJsBundle = compiled.js;
        }

        <?php if (isset($_GET['autorecompile'])) { ?>
        setTimeout(function () {
          window.BootscoreTSCompiler.Compile({}, bootscoreCompilingFinishedJs);
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
        window.BootscoreTSCompiler.Compile({}, bootscoreCompilingFinishedJs);
      });
    </script>
  <?php
});

// AJAX HANDLER: SAVE COMPILED JS TO assets/js/bootscore.min.js
add_action('wp_ajax_bootscore_save_js_bundle', function () {
  if (!is_user_logged_in() || !current_user_can('administrator')) return;

  check_ajax_referer('bootscore_save_js_bundle', 'nonce');

  $compiled_js = stripslashes($_POST['js']);

  // Shared with the SCSS compiler - off by default, enable with:
  // add_filter('bootscore/compiler/enable_sourcemap', '__return_true');
  $enable_sourcemap = apply_filters('bootscore/compiler/enable_sourcemap', false);
  $has_sourcemap    = $enable_sourcemap && isset($_POST['sourceMap']) && $_POST['sourceMap'] !== "";

  if ($has_sourcemap) {
    $compiled_js .= "\n//# sourceMappingURL=bootscore.min.js.map";
  }

  global $wp_filesystem;
  if (empty($wp_filesystem)) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    WP_Filesystem();
  }

  $js_file = bootscore_ts_compiler_js_file();
  $js_dir  = dirname($js_file);

  if (!file_exists($js_dir)) {
    wp_mkdir_p($js_dir);
  }

  $saved = $wp_filesystem->put_contents($js_file, $compiled_js, FS_CHMOD_FILE);

  if ($saved && $has_sourcemap) {
    $wp_filesystem->put_contents($js_file . '.map', stripslashes($_POST['sourceMap']), FS_CHMOD_FILE);
  }

  echo $saved ? "New JS bundle successfully saved." : "Error writing JS file.";

  wp_die();
});

// ADMIN BAR TRIGGER
add_action('admin_bar_menu', function ($admin_bar) {
  if (!current_user_can('administrator')) return;

  $base_args = array('compile_ts' => '1', 'ts_nocache' => '1');

  if (!isset($_GET['autorecompile'])) {
    $admin_bar->add_node(array(
      'id'    => 'bootscore-recompile-ts',
      'title' => __('TS Compiler', 'bootscore'),
      'href'  => add_query_arg($base_args),
    ));
    $admin_bar->add_node(array(
      'id'     => 'bootscore-recompile-ts-once',
      'parent' => 'bootscore-recompile-ts',
      'title'  => __('Recompile Once', 'bootscore'),
      'href'   => add_query_arg($base_args),
    ));
    $admin_bar->add_node(array(
      'id'     => 'bootscore-recompile-ts-auto',
      'parent' => 'bootscore-recompile-ts',
      'title'  => __('Recompile Continuously', 'bootscore'),
      'href'   => add_query_arg(array_merge($base_args, array('autorecompile' => '1'))),
    ));
  } else {
    $admin_bar->add_node(array(
      'id'    => 'bootscore-recompile-ts',
      'title' => __('Stop TS Compiler', 'bootscore'),
      'href'  => add_query_arg(array('compile_ts' => false, 'ts_nocache' => false, 'autorecompile' => false)),
    ));
  }
}, 100);
