<?php
/**
 * Bootscore Update Checker
 * Shared update library for Bootscore products
 * Supports: Custom server (private) + GitHub public releases
 *
 * @package Bootscore_Update_Checker
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
     * Format GitHub release data to our standard format
     *
     * @param object $github_data GitHub API response
     * @param object $product     Product object
     * @return object
     */
    private function format_github_data($github_data, $product) {
      // Remove 'v' prefix from version if present
      $tag = ltrim($github_data->tag_name, 'v');

      // Build download URL - look for a .zip asset
      $download_url = '';
      if (!empty($github_data->assets)) {
        foreach ($github_data->assets as $asset) {
          if (strpos($asset->name, '.zip') !== false) {
            $download_url = $asset->browser_download_url;
            break;
          }
        }
      }

      // If no asset found, build standard GitHub download URL
      if (empty($download_url)) {
        $download_url = "https://github.com/{$product->github_repo}/releases/download/{$github_data->tag_name}/{$product->slug}.zip";
      }

      // Extract description from release body
      $description = $github_data->body ?? 'No description provided.';
      $changelog = $github_data->body ?? '';

      return (object) array(
        'name' => $product->name,
        'slug' => $product->slug,
        'version' => $tag,
        'download_url' => $download_url,
        'tested' => '6.4', // GitHub doesn't provide this, use default
        'requires' => '5.0', // GitHub doesn't provide this, use default
        'requires_php' => '7.4', // GitHub doesn't provide this, use default
        'last_updated' => $github_data->published_at ?? date('Y-m-d H:i:s'),
        'sections' => array(
          'description' => $description,
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
      $res->tested = $remote->tested ?? '';
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
        $res->tested = $update->tested ?? '';
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
