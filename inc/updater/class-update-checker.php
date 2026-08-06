<?php
/**
 * Bootscore Update Checker
 * Shared update library for Bootscore products
 * Supports: Custom server (private) + GitHub public releases
 * 
 * Inspired by https://rudrastyh.com/wordpress/self-hosted-plugin-update.html
 *
 * @package Bootscore
 * @version 6.5.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


if (!class_exists('Bootscore_Update_Checker')) {

  class Bootscore_Update_Checker {

    /**
     * @var int Cache duration in seconds
     */
    private $cache_time;

    /**
     * @var array Registered products
     */
    private $products = array();

    /**
     * Constructor
     *
     * @param int $cache_time Cache duration in seconds
     */
    public function __construct($cache_time = 43200) {
      $this->cache_time = $cache_time;

      // Hook into WordPress
      add_filter('plugins_api', array($this, 'plugin_info'), 20, 3);
      add_filter('site_transient_update_plugins', array($this, 'plugin_updates'));
      add_filter('pre_set_site_transient_update_themes', array($this, 'theme_updates'));
      add_action('upgrader_process_complete', array($this, 'clear_cache'), 10, 2);
    }

    /**
     * Register a product for updates
     *
     * @param array $product {
     *     @type string 'type'            'plugin' or 'theme'
     *     @type string 'slug'            Unique product slug
     *     @type string 'current_version' Current installed version
     *     @type string 'file'            Plugin file path or theme slug
     *     @type string 'source'          'custom' or 'github' (default: 'custom')
     *     @type string 'info_url'        Full URL to info.json (for 'custom' source)
     *     @type string 'github_repo'     GitHub repo 'owner/repo' (for 'github' source)
     *     @type string 'name'            Product name
     *     @type string 'requires'        Optional min WP version override (GitHub source only;
     *                                     'custom' source always reads this from info.json)
     *     @type string 'tested'          Optional "tested up to" WP version override (GitHub source only)
     *     @type string 'requires_php'    Optional min PHP version override (GitHub source only)
     * }
     */
    public function register_product($product) {
      $defaults = array(
        'type' => 'plugin',
        'slug' => '',
        'current_version' => '0.0.0',
        'file' => '',
        'source' => 'custom', // 'custom' or 'github'
        'info_url' => '',
        'github_repo' => '',
        'name' => '',
        'requires' => '',
        'tested' => '',
        'requires_php' => '',
      );

      $product = wp_parse_args($product, $defaults);

      if (empty($product['slug'])) {
        return;
      }

      if ($product['source'] === 'custom' && empty($product['info_url'])) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
          error_log("Bootscore_Update_Checker: missing 'info_url' for product '{$product['slug']}'.");
        }
        return;
      }

      // Auto-detect name if not provided
      if (empty($product['name'])) {
        $product['name'] = ucwords(str_replace('-', ' ', $product['slug']));
      }

      $this->products[$product['slug']] = (object) $product;
    }

    /**
     * Get product by slug
     *
     * @param string $slug Product slug
     * @return object|null
     */
    private function get_product($slug) {
      return isset($this->products[$slug]) ? $this->products[$slug] : null;
    }

    /**
     * Normalize an 'icons' object from remote data into a plain array,
     * keeping only the keys WordPress recognizes and that are actually set.
     *
     * @param object|null $icons Raw icons object from remote info
     * @return array
     */
    private function format_icons($icons) {
      if (empty($icons)) {
        return array();
      }

      $result = array();

      foreach (array('svg', '2x', '1x', 'default') as $key) {
        if (!empty($icons->{$key})) {
          $result[$key] = $icons->{$key};
        }
      }

      return $result;
    }

    /**
     * Look for icon files bundled in the plugin's own 'assets' folder
     * (e.g. assets/icon.svg), so icons don't need to be duplicated on
     * the update server. Only applies to 'plugin' type products.
     *
     * @param object $product Product object
     * @return array
     */
    private function get_local_icons($product) {
      if ($product->type !== 'plugin' || empty($product->file)) {
        return array();
      }

      $plugin_file = trailingslashit(WP_PLUGIN_DIR) . $product->file;
      $plugin_dir = dirname($plugin_file);

      $map = array(
        'svg' => 'assets/icon.svg',
      );

      $icons = array();

      foreach ($map as $key => $relative_path) {
        if (file_exists($plugin_dir . '/' . $relative_path)) {
          $icons[$key] = plugins_url($relative_path, $plugin_file);
        }
      }

      return $icons;
    }

    /**
     * Get remote product info - chooses source based on 'source' field
     *
     * @param string $slug Product slug
     * @param bool   $force Force refresh
     * @return object|null
     */
    private function get_remote_info($slug, $force = false) {
      $product = $this->get_product($slug);
      if (!$product) {
        return null;
      }

      // Route to the appropriate source
      if ($product->source === 'github') {
        return $this->get_github_info($slug, $force);
      }

      // Default: custom server
      return $this->get_custom_server_info($slug, $force);
    }

    /**
     * Get info from custom server (for private repos)
     *
     * @param string $slug Product slug
     * @param bool   $force Force refresh
     * @return object|null
     */
    private function get_custom_server_info($slug, $force = false) {
      $product = $this->get_product($slug);
      if (!$product || empty($product->info_url)) {
        return null;
      }

      $cache_key = 'bootscore_custom_' . $slug;
      $cached = get_transient($cache_key);

      if ($cached !== false && !$force) {
        return $cached;
      }

      $response = wp_remote_get($product->info_url, array('timeout' => 10));

      if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return null;
      }

      $data = json_decode(wp_remote_retrieve_body($response));

      if (!empty($data)) {
        set_transient($cache_key, $data, $this->cache_time);
      }

      return $data;
    }

    /**
     * Get info from public GitHub API (no token needed!)
     *
     * @param string $slug Product slug
     * @param bool   $force Force refresh
     * @return object|null
     */
    private function get_github_info($slug, $force = false) {
      $product = $this->get_product($slug);

      // GitHub repo must be set for GitHub source
      if (empty($product->github_repo)) {
        return null;
      }

      $cache_key = 'bootscore_github_' . $slug;
      $cached = get_transient($cache_key);

      if ($cached !== false && !$force) {
        return $cached;
      }

      // GitHub API - NO TOKEN NEEDED for public repos!
      // Note: /releases/latest already excludes draft and pre-release releases automatically,
      // so beta/rc tags won't trigger an update unless explicitly published as the latest release.
      $url = "https://api.github.com/repos/{$product->github_repo}/releases/latest";

      $response = wp_remote_get($url, array(
        'timeout' => 10,
        'headers' => array(
          'Accept' => 'application/json',
          'User-Agent' => 'Bootscore-Updater/1.0',
        ),
      ));

      if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return null;
      }

      $github_data = json_decode(wp_remote_retrieve_body($response));

      if (empty($github_data) || empty($github_data->tag_name)) {
        return null;
      }

      // Transform GitHub data to our format
      $data = $this->format_github_data($github_data, $product);

      set_transient($cache_key, $data, $this->cache_time);

      return $data;
    }

    /**
     * Split a GitHub release body into 'description' and 'changelog' sections,
     * based on '## Description' / '## Changelog' headings. Falls back to
     * treating the whole body as the description if no headings are found.
     *
     * @param string $body Raw release body (Markdown)
     * @return array{description: string, changelog: string}
     */
    private function parse_release_sections($body) {
      if (empty($body)) {
        return array('description' => '', 'changelog' => '');
      }

      $sections = array();

      if (preg_match_all('/^##\s*(description|changelog)\s*$/im', $body, $matches, PREG_OFFSET_CAPTURE)) {
        $count = count($matches[0]);

        for ($i = 0; $i < $count; $i++) {
          $label = strtolower($matches[1][$i][0]);
          $start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
          $end = ($i + 1 < $count) ? $matches[0][$i + 1][1] : strlen($body);
          $sections[$label] = trim(substr($body, $start, $end - $start));
        }
      }

      // No recognized headings at all - treat the whole body as the description
      if (empty($sections)) {
        return array('description' => trim($body), 'changelog' => '');
      }

      return array(
        'description' => $sections['description'] ?? '',
        'changelog' => $sections['changelog'] ?? '',
      );
    }

    /**
     * Very small Markdown-lite to HTML converter for release note sections.
     * Handles paragraphs and '- ' / '* ' bullet lists; escapes everything else.
     *
     * @param string $text Plain/lite-Markdown text
     * @return string HTML
     */
    private function markdown_lite_to_html($text) {
      if (empty($text)) {
        return '';
      }

      $lines = preg_split('/\r\n|\r|\n/', trim($text));
      $html = '';
      $in_list = false;

      foreach ($lines as $line) {
        $trimmed = trim($line);

        if ($trimmed === '') {
          continue;
        }

        // '= Version - Date =' changelog entry heading
        if (preg_match('/^=\s*(.+?)\s*=$/', $trimmed, $m)) {
          if ($in_list) {
            $html .= '</ul>';
            $in_list = false;
          }
          $html .= '<h4>' . esc_html($m[1]) . '</h4>';
          continue;
        }

        // '#### Sub heading' style
        if (preg_match('/^#{2,4}\s*(.+)$/', $trimmed, $m)) {
          if ($in_list) {
            $html .= '</ul>';
            $in_list = false;
          }
          $html .= '<h5>' . esc_html($m[1]) . '</h5>';
          continue;
        }

        if (preg_match('/^[-*]\s+(.+)$/', $trimmed, $m)) {
          if (!$in_list) {
            $html .= '<ul>';
            $in_list = true;
          }
          $html .= '<li>' . esc_html($m[1]) . '</li>';
          continue;
        }

        if ($in_list) {
          $html .= '</ul>';
          $in_list = false;
        }

        $html .= '<p>' . esc_html($trimmed) . '</p>';
      }

      if ($in_list) {
        $html .= '</ul>';
      }

      return $html;
    }

    /**
     * Resolve the 'tested' value actually shown/compared against.
     *
     * WordPress core compares 'tested' literally against the site's full
     * running version, both for the Plugins-list "Not tested" badge and the
     * popup's compatibility warning. If the developer's declared value
     * already covers the site's version, use it as-is (the honest, accurate
     * number). Only when the site is running something newer than declared,
     * fall back to the site's own real, actual version - never an invented
     * placeholder like '7.0.9', which doesn't correspond to any real release.
     *
     * @param string $tested Declared 'tested' value (from readme.txt/info.json)
     * @return string
     */
    private function resolve_tested_version($tested) {
      $current = get_bloginfo('version');

      if (empty($tested)) {
        return $current;
      }

      if (version_compare($current, $tested, '<=')) {
        return $tested;
      }

      return $current;
    }

    /**
     * Get the site's WP version truncated to major.minor (e.g. '7.0.1' -> '7.0'),
     * matching the WordPress convention used for 'Tested up to' declarations.
     *
     * @return string
     */
    private function get_wp_major_minor() {
      $version = get_bloginfo('version');
      $parts = explode('.', $version);
      return implode('.', array_slice($parts, 0, 2));
    }

    /**
     * Fetch a public repo's readme.txt at the exact release tag (not 'main'),
     * so the docs shown always match the version being offered as an update.
     *
     * @param object $product  Product object
     * @param string $tag_name Raw GitHub tag name (e.g. 'v0.4.0')
     * @return string|null Raw readme.txt content, or null if unavailable
     */
    private function fetch_github_readme($product, $tag_name) {
      $url = "https://raw.githubusercontent.com/{$product->github_repo}/{$tag_name}/readme.txt";

      $response = wp_remote_get($url, array('timeout' => 10));

      if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return null;
      }

      $body = wp_remote_retrieve_body($response);

      return !empty($body) ? $body : null;
    }

    /**
     * Parse a standard WordPress-format readme.txt into header fields
     * (Tested up to / Requires at least / Requires PHP) and sections
     * (Description / Installation / Changelog).
     *
     * @param string|null $readme Raw readme.txt content
     * @return array|null
     */
    private function parse_readme($readme) {
      if (empty($readme)) {
        return null;
      }

      $result = array(
        'requires' => '',
        'tested' => '',
        'requires_php' => '',
        'sections' => array(
          'description' => '',
          'installation' => '',
          'changelog' => '',
        ),
      );

      // '== Section ==' headings only - excludes '=== Title ===' and
      // '= Changelog entry =' via the [^=] guard right after the opening '=='
      $heading_pattern = '/^==\s*([^=].*?)\s*==$/m';

      // Header fields (Tested up to, Requires at least, Requires PHP) live
      // before the first section heading
      $header = $readme;
      if (preg_match($heading_pattern, $readme, $first_match, PREG_OFFSET_CAPTURE)) {
        $header = substr($readme, 0, $first_match[0][1]);
      }

      if (preg_match('/^\s*Tested up to:\s*(.+)$/im', $header, $m)) {
        $result['tested'] = trim($m[1]);
      }
      if (preg_match('/^\s*Requires at least:\s*(.+)$/im', $header, $m)) {
        $result['requires'] = trim($m[1]);
      }
      if (preg_match('/^\s*Requires PHP:\s*(.+)$/im', $header, $m)) {
        $result['requires_php'] = trim($m[1]);
      }

      // Sections
      if (preg_match_all($heading_pattern, $readme, $matches, PREG_OFFSET_CAPTURE)) {
        $count = count($matches[0]);

        for ($i = 0; $i < $count; $i++) {
          $label = strtolower(trim($matches[1][$i][0]));
          $start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
          $end = ($i + 1 < $count) ? $matches[0][$i + 1][1] : strlen($readme);
          $content = trim(substr($readme, $start, $end - $start));

          if (isset($result['sections'][$label])) {
            $result['sections'][$label] = $content;
          }
        }
      }

      return $result;
    }

    /**
     * Format GitHub release data to our standard format
     *
     * @param object $github_data GitHub API response
     * @param object $product     Product object
     * @return object
     */
    private function format_github_data($github_data, $product) {
      // Strip a single leading 'v' or 'V' prefix, only when followed by a digit
      // (e.g. 'v1.2.3' -> '1.2.3', but leaves oddly-named tags alone)
      $tag = preg_replace('/^[vV](?=\d)/', '', $github_data->tag_name);

      // Build download URL - prefer an asset matching the exact plugin slug,
      // fall back to the first .zip asset if no exact match is found
      $download_url = '';
      if (!empty($github_data->assets)) {
        $expected_name = $product->slug . '.zip';

        foreach ($github_data->assets as $asset) {
          if ($asset->name === $expected_name) {
            $download_url = $asset->browser_download_url;
            break;
          }
        }

        if (empty($download_url)) {
          foreach ($github_data->assets as $asset) {
            if (substr($asset->name, -4) === '.zip') {
              $download_url = $asset->browser_download_url;
              break;
            }
          }
        }
      }

      // If no asset found, build standard GitHub download URL
      // (uses the original, unstripped tag_name, since that's the real tag path)
      if (empty($download_url)) {
        $download_url = "https://github.com/{$product->github_repo}/releases/download/{$github_data->tag_name}/{$product->slug}.zip";
      }

      // Prefer the repo's readme.txt at the exact release tag - it's the
      // standard WP format, includes Installation, and gives real
      // Tested up to / Requires at least / Requires PHP values.
      // Falls back to parsing the release notes body if no readme.txt is found.
      $readme = $this->parse_readme($this->fetch_github_readme($product, $github_data->tag_name));

      if ($readme) {
        $description = !empty($readme['sections']['description'])
          ? $this->markdown_lite_to_html($readme['sections']['description'])
          : 'No description provided.';
        $installation = !empty($readme['sections']['installation'])
          ? $this->markdown_lite_to_html($readme['sections']['installation'])
          : '';
        $changelog = !empty($readme['sections']['changelog'])
          ? $this->markdown_lite_to_html($readme['sections']['changelog'])
          : '';
        $tested = $product->tested ?: ($readme['tested'] ?: $this->get_wp_major_minor());
        $requires = $product->requires ?: ($readme['requires'] ?: '5.0');
        $requires_php = $product->requires_php ?: ($readme['requires_php'] ?: '7.4');
      } else {
        // Split release notes into description/changelog based on ## headings
        $sections = $this->parse_release_sections($github_data->body ?? '');
        $description = !empty($sections['description'])
          ? $this->markdown_lite_to_html($sections['description'])
          : 'No description provided.';
        $installation = '';
        $changelog = !empty($sections['changelog'])
          ? $this->markdown_lite_to_html($sections['changelog'])
          : '';
        $tested = $product->tested ?: $this->get_wp_major_minor();
        $requires = $product->requires ?: '5.0';
        $requires_php = $product->requires_php ?: '7.4';
      }

      return (object) array(
        'name' => $product->name,
        'slug' => $product->slug,
        'version' => $tag,
        'download_url' => $download_url,
        'tested' => $tested,
        'requires' => $requires,
        'requires_php' => $requires_php,
        'last_updated' => $github_data->published_at ?? date('Y-m-d H:i:s'),
        'sections' => (object) array(
          'description' => $description,
          'installation' => $installation,
          'changelog' => $changelog,
        ),
        'banners' => array(),
        'icons' => array(),
        'homepage' => "https://github.com/{$product->github_repo}",
      );
    }

    /**
     * Check if update is available
     *
     * @param string $slug Product slug
     * @param bool   $force Force check
     * @return object|false
     */
    public function check_for_update($slug, $force = false) {
      $product = $this->get_product($slug);
      if (!$product) {
        return false;
      }

      $remote = $this->get_remote_info($slug, $force);
      if (!$remote) {
        return false;
      }

      // Check if version is newer
      if (!version_compare($product->current_version, $remote->version, '<')) {
        return false;
      }

      // Check WordPress requirements
      if (isset($remote->requires) && version_compare(get_bloginfo('version'), $remote->requires, '<')) {
        return false;
      }

      // Check PHP requirements
      if (isset($remote->requires_php) && version_compare(PHP_VERSION, $remote->requires_php, '<')) {
        return false;
      }

      return $remote;
    }

    /**
     * Plugin info for "View Details" popup
     */
    public function plugin_info($res, $action, $args) {
      if ('plugin_information' !== $action) {
        return $res;
      }

      $product = $this->get_product($args->slug);
      if (!$product || $product->type !== 'plugin') {
        return $res;
      }

      $remote = $this->get_remote_info($args->slug);
      if (!$remote) {
        return $res;
      }

      $res = new stdClass();
      $res->name = $remote->name ?? $product->name;
      $res->slug = $remote->slug ?? $args->slug;
      $res->version = $remote->version ?? '0.0.0';
      $res->tested = $this->resolve_tested_version($remote->tested ?? '');
      $res->requires = $remote->requires ?? '';
      $res->author = $remote->author ?? '';
      $res->author_profile = $remote->author_profile ?? '';
      $res->download_link = $remote->download_url ?? '';
      $res->trunk = $res->download_link;
      $res->requires_php = $remote->requires_php ?? '';
      $res->last_updated = $remote->last_updated ?? '';

      $res->sections = array(
        'description' => $remote->sections->description ?? 'No description available.',
        'installation' => $remote->sections->installation ?? '',
        'changelog' => $remote->sections->changelog ?? '',
      );

      if (!empty($remote->banners)) {
        $res->banners = array(
          'low' => $remote->banners->low ?? '',
          'high' => $remote->banners->high ?? '',
        );
      }

      $icons = $this->get_local_icons($product);
      if (empty($icons)) {
        $icons = $this->format_icons($remote->icons ?? null);
      }
      if (!empty($icons)) {
        $res->icons = $icons;
      }

      return $res;
    }

    /**
     * Plugin updates
     */
    public function plugin_updates($transient) {
      if (empty($transient->checked)) {
        return $transient;
      }

      foreach ($this->products as $slug => $product) {
        if ($product->type !== 'plugin') {
          continue;
        }

        $update = $this->check_for_update($slug);
        if (!$update) {
          continue;
        }

        $res = new stdClass();
        $res->slug = $slug;
        $res->plugin = $product->file;
        $res->new_version = $update->version;
        // No automatic padding here - keep 'Tested up to' in readme.txt /
        // info.json genuinely current instead. WordPress core compares this
        // value literally against the site's full running version (both for
        // the Plugins-list "Not tested" badge and the popup warning), so an
        // accurate, deliberately up-to-date value here satisfies both checks
        // honestly, with no synthetic values needed.
        $res->tested = $this->resolve_tested_version($update->tested ?? '');
        $res->package = $update->download_url ?? '';

        if (isset($update->requires)) {
          $res->requires = $update->requires;
        }

        if (isset($update->requires_php)) {
          $res->requires_php = $update->requires_php;
        }

        $icons = $this->get_local_icons($product);
        if (empty($icons)) {
          $icons = $this->format_icons($update->icons ?? null);
        }
        if (!empty($icons)) {
          $res->icons = $icons;
        }

        $transient->response[$product->file] = $res;
      }

      return $transient;
    }

    /**
     * Theme updates
     */
    public function theme_updates($transient) {
      foreach ($this->products as $slug => $product) {
        if ($product->type !== 'theme') {
          continue;
        }

        $update = $this->check_for_update($slug);
        if (!$update) {
          continue;
        }

        $transient->response[$slug] = array(
          'theme' => $slug,
          'new_version' => $update->version,
          'package' => $update->download_url ?? '',
          'url' => $update->homepage ?? '',
        );
      }

      return $transient;
    }

    /**
     * Clear cache after updates
     */
    public function clear_cache() {
      foreach ($this->products as $slug => $product) {
        delete_transient('bootscore_custom_' . $slug);
        delete_transient('bootscore_github_' . $slug);
        delete_transient('bootscore_update_' . $slug); // Legacy
      }
    }
  }
}
