<?php

/**
 * Plugin Name: Wiki Prayer Times Widget
 * Description: A responsive prayer times widget that displays Islamic prayer times with countdown timer.
 * Version: 1.1.1
 * Author: Arnel Go
 * Author URI: https://arnelgo.info/
 * Plugin URI: https://github.com/wikiwyrhead/wiki-islamic-prayer-times
 * GitHub Plugin URI: https://github.com/wikiwyrhead/wiki-islamic-prayer-times
 * Text Domain: wiki-prayer-times
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.0
 *
 * @package Wiki_Prayer_Times
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WPT_VERSION', '1.1.1');
define('WPT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once WPT_PLUGIN_DIR . 'includes/class-wiki-prayer-times-settings.php';

// Initialize settings
function wiki_prayer_times_init()
{
    new Wiki_Prayer_Times_Settings();
}
add_action('plugins_loaded', 'wiki_prayer_times_init');

// Enqueue scripts and styles
function wiki_prayer_times_enqueue_scripts()
{
    wp_enqueue_style('wiki-prayer-times', WPT_PLUGIN_URL . 'assets/css/wiki-prayer-times.css', array(), WPT_VERSION);
    wp_enqueue_script('wiki-prayer-times', WPT_PLUGIN_URL . 'assets/js/wiki-prayer-times.js', array('jquery'), WPT_VERSION, true);

    // Get settings
    $settings = get_option('wiki_prayer_times_settings', array());

    // Localize script with settings
    wp_localize_script('wiki-prayer-times', 'wptSettings', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wpt_nonce'),
        'defaultCity' => isset($settings['default_city']) ? $settings['default_city'] : 'London',
        'defaultCountry' => isset($settings['default_country']) ? $settings['default_country'] : 'United Kingdom',
        'calculationMethod' => isset($settings['calculation_method']) ? $settings['calculation_method'] : '5',
        'cacheDuration' => isset($settings['cache_duration']) ? $settings['cache_duration'] : 3600,
        'showHijriDate' => isset($settings['show_hijri_date']) ? $settings['show_hijri_date'] : true,
        'showCountdown' => isset($settings['show_countdown']) ? $settings['show_countdown'] : true,
        'customCSS' => isset($settings['custom_css']) ? $settings['custom_css'] : '',
        'timeFormat' => isset($settings['time_format']) ? $settings['time_format'] : '24h',
        'highlightNextPrayer' => isset($settings['highlight_next_prayer']) ? $settings['highlight_next_prayer'] : true,
        'refreshInterval' => isset($settings['refresh_interval']) ? $settings['refresh_interval'] : 60
    ));
}
add_action('wp_enqueue_scripts', 'wiki_prayer_times_enqueue_scripts');

// Register shortcode
function wiki_prayer_times_shortcode($atts)
{
    $settings = get_option('wiki_prayer_times_settings', array());
    $atts = shortcode_atts(array(
        'city' => isset($settings['default_city']) ? $settings['default_city'] : 'London',
        'country' => isset($settings['default_country']) ? $settings['default_country'] : 'United Kingdom',
        'method' => isset($settings['calculation_method']) ? $settings['calculation_method'] : '5',
        'show_hijri' => isset($settings['show_hijri_date']) ? $settings['show_hijri_date'] : true,
        'show_countdown' => isset($settings['show_countdown']) ? $settings['show_countdown'] : true,
        'highlight_next' => isset($settings['highlight_next_prayer']) ? $settings['highlight_next_prayer'] : true
    ), $atts, 'wiki_prayer_times');

    ob_start();
?>
    <div class="wiki-prayer-times-widget"
        data-city="<?php echo esc_attr($atts['city']); ?>"
        data-country="<?php echo esc_attr($atts['country']); ?>"
        data-method="<?php echo esc_attr($atts['method']); ?>"
        data-show-hijri="<?php echo esc_attr($atts['show_hijri']); ?>"
        data-show-countdown="<?php echo esc_attr($atts['show_countdown']); ?>"
        data-highlight-next="<?php echo esc_attr($atts['highlight_next']); ?>">
        <div id="wptPrayerTimes" class="prayer-list">
            <p>Loading...</p>
        </div>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('wiki_prayer_times', 'wiki_prayer_times_shortcode');

// Add custom CSS to head
function wiki_prayer_times_add_custom_css()
{
    $settings = get_option('wiki_prayer_times_settings', array());
    if (!empty($settings['custom_css'])) {
        echo '<style type="text/css">' . wp_kses_post($settings['custom_css']) . '</style>';
    }
}
add_action('wp_head', 'wiki_prayer_times_add_custom_css');
