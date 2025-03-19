<?php

/**
 * Wiki Prayer Times Settings Class
 *
 * @package Wiki_Prayer_Times
 */

if (!defined('ABSPATH')) {
    exit;
}

class Wiki_Prayer_Times_Settings
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Add settings page to WordPress admin
     */
    public function add_settings_page()
    {
        add_options_page(
            'Wiki Prayer Times Settings',
            'Wiki Prayer Times',
            'manage_options',
            'wiki-prayer-times',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings()
    {
        register_setting('wiki_prayer_times_settings', 'wiki_prayer_times_settings', array($this, 'sanitize_settings'));

        // General Settings Section
        add_settings_section(
            'wpt_general_section',
            'General Settings',
            array($this, 'render_general_section'),
            'wiki-prayer-times'
        );

        // Display Settings Section
        add_settings_section(
            'wpt_display_section',
            'Display Settings',
            array($this, 'render_display_section'),
            'wiki-prayer-times'
        );

        // Cache Settings Section
        add_settings_section(
            'wpt_cache_section',
            'Cache Settings',
            array($this, 'render_cache_section'),
            'wiki-prayer-times'
        );

        // Customization Section
        add_settings_section(
            'wpt_customization_section',
            'Customization',
            array($this, 'render_customization_section'),
            'wiki-prayer-times'
        );

        // Add settings fields
        $this->add_settings_fields();
    }

    /**
     * Add settings fields
     */
    private function add_settings_fields()
    {
        // General Settings Fields
        add_settings_field(
            'default_city',
            'Default City',
            array($this, 'render_text_field'),
            'wiki-prayer-times',
            'wpt_general_section',
            array('label_for' => 'default_city')
        );

        add_settings_field(
            'default_country',
            'Default Country',
            array($this, 'render_text_field'),
            'wiki-prayer-times',
            'wpt_general_section',
            array('label_for' => 'default_country')
        );

        add_settings_field(
            'calculation_method',
            'Calculation Method',
            array($this, 'render_select_field'),
            'wiki-prayer-times',
            'wpt_general_section',
            array(
                'label_for' => 'calculation_method',
                'options' => $this->get_calculation_methods()
            )
        );

        // Display Settings Fields
        add_settings_field(
            'show_hijri_date',
            'Show Hijri Date',
            array($this, 'render_checkbox_field'),
            'wiki-prayer-times',
            'wpt_display_section',
            array('label_for' => 'show_hijri_date')
        );

        add_settings_field(
            'show_countdown',
            'Show Countdown',
            array($this, 'render_checkbox_field'),
            'wiki-prayer-times',
            'wpt_display_section',
            array('label_for' => 'show_countdown')
        );

        add_settings_field(
            'time_format',
            'Time Format',
            array($this, 'render_select_field'),
            'wiki-prayer-times',
            'wpt_display_section',
            array(
                'label_for' => 'time_format',
                'options' => array(
                    '24h' => '24-hour format',
                    '12h' => '12-hour format'
                )
            )
        );

        add_settings_field(
            'highlight_next_prayer',
            'Highlight Next Prayer',
            array($this, 'render_checkbox_field'),
            'wiki-prayer-times',
            'wpt_display_section',
            array('label_for' => 'highlight_next_prayer')
        );

        // Cache Settings Fields
        add_settings_field(
            'cache_duration',
            'Cache Duration (seconds)',
            array($this, 'render_number_field'),
            'wiki-prayer-times',
            'wpt_cache_section',
            array(
                'label_for' => 'cache_duration',
                'min' => 300,
                'max' => 86400,
                'step' => 300
            )
        );

        add_settings_field(
            'refresh_interval',
            'Refresh Interval (seconds)',
            array($this, 'render_number_field'),
            'wiki-prayer-times',
            'wpt_cache_section',
            array(
                'label_for' => 'refresh_interval',
                'min' => 30,
                'max' => 3600,
                'step' => 30
            )
        );

        // Customization Fields
        add_settings_field(
            'custom_css',
            'Custom CSS',
            array($this, 'render_textarea_field'),
            'wiki-prayer-times',
            'wpt_customization_section',
            array('label_for' => 'custom_css')
        );
    }

    /**
     * Get calculation methods
     */
    private function get_calculation_methods()
    {
        return array(
            '1' => 'Egyptian General Authority',
            '2' => 'Islamic Society of North America',
            '3' => 'Muslim World League',
            '4' => 'University of Islamic Sciences, Karachi',
            '5' => 'Islamic University of Karachi',
            '6' => 'Institute of Geophysics, University of Tehran',
            '7' => 'Shia Ithna-Ashari, Leva Institute, Qum',
            '8' => 'Gulf Region',
            '9' => 'Kuwait',
            '10' => 'Qatar',
            '11' => 'Singapore',
            '12' => 'Tehran',
            '13' => 'Custom'
        );
    }

    /**
     * Render settings page
     */
    public function render_settings_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
?>
        <div class="wrap wiki-prayer-times-settings">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="wiki-prayer-times-grid">
                <!-- Instructions Card -->
                <div class="settings-card instructions-card">
                    <h2><span class="dashicons dashicons-book"></span> Instructions</h2>
                    <div class="card-content">
                        <h3>How to Use the Prayer Times Widget</h3>
                        <p>There are three ways to add the prayer times widget to your site:</p>

                        <h4>1. Using the Shortcode</h4>
                        <p>Add the widget anywhere using the shortcode:</p>
                        <code>[wiki_prayer_times]</code>

                        <h4>2. Customized Shortcode</h4>
                        <p>You can customize individual widgets using parameters:</p>
                        <code>[wiki_prayer_times city="London" country="United Kingdom" method="5" show_hijri="true" show_countdown="true"]</code>
                        <p>Available parameters:</p>
                        <ul>
                            <li><code>city</code> - Name of the city</li>
                            <li><code>country</code> - Name of the country</li>
                            <li><code>method</code> - Calculation method (1-13)</li>
                            <li><code>show_hijri</code> - Show Hijri date (true/false)</li>
                            <li><code>show_countdown</code> - Show countdown timer (true/false)</li>
                            <li><code>highlight_next</code> - Highlight next prayer (true/false)</li>
                        </ul>

                        <h4>3. Using PHP in Your Theme</h4>
                        <p>Add this code to your theme files:</p>
                        <code>echo do_shortcode('[wiki_prayer_times]');</code>
                    </div>
                </div>

                <!-- Settings Form -->
                <div class="settings-card settings-form">
                    <form action="options.php" method="post">
                        <?php
                        settings_fields('wiki_prayer_times_settings');
                        do_settings_sections('wiki-prayer-times');
                        submit_button('Save Settings', 'primary large');
                        ?>
                    </form>
                </div>

                <!-- Credits Card -->
                <div class="settings-card credits-card">
                    <h2><span class="dashicons dashicons-info"></span> Credits</h2>
                    <div class="card-content">
                        <p>This plugin uses the <a href="https://aladhan.com/prayer-times-api" target="_blank">Al Adhan Prayer Times API</a> to calculate accurate prayer times.</p>
                        <p>API Features:</p>
                        <ul>
                            <li>Reliable prayer time calculations</li>
                            <li>Support for multiple calculation methods</li>
                            <li>Hijri date conversion</li>
                            <li>Worldwide location support</li>
                        </ul>
                        <p class="api-credit">Powered by <a href="https://aladhan.com" target="_blank">AlAdhan.com</a></p>
                    </div>
                </div>

                <!-- Donation Card -->
                <div class="settings-card donation-card">
                    <h2><span class="dashicons dashicons-heart"></span> Support the Development</h2>
                    <div class="card-content">
                        <div class="donation-content">
                            <p>If you find this plugin useful, please consider making a donation to support continued development and maintenance. Your contribution helps keep this plugin updated and compatible with the latest WordPress versions.</p>
                            <div class="donation-button">
                                <a href="https://www.paypal.com/paypalme/arnelborresgo" target="_blank" class="button button-primary">
                                    <span class="dashicons dashicons-paypal"></span>
                                    Donate with PayPal
                                </a>
                            </div>
                            <p class="donation-email">PayPal Email: arnel.b.go@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .wiki-prayer-times-settings {
                    max-width: 1200px;
                    margin: 20px auto;
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                }

                .wiki-prayer-times-settings h1 {
                    color: #1d2327;
                    font-size: 23px;
                    font-weight: 400;
                    margin: 0;
                    padding: 9px 0 4px 0;
                    line-height: 1.3;
                }

                .wiki-prayer-times-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                    margin: 20px 0;
                }

                .settings-card {
                    background: #fff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 20px;
                    text-align: left;
                }

                .settings-card h2 {
                    margin-top: 0;
                    font-size: 20px;
                    color: #23282d;
                }

                .donation-card {
                    grid-column: 2 / 3;
                    margin-top: 24px;
                }

                .donation-content {
                    text-align: center;
                }

                .donation-button {
                    margin: 20px 0;
                }

                .donation-button .button {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    padding: 8px 20px;
                    height: auto;
                    font-size: 14px;
                    line-height: 2;
                    text-decoration: none;
                    background-color: #0073aa;
                    border-color: #0073aa;
                    color: #fff;
                    border-radius: 4px;
                    transition: all 0.3s ease;
                }

                .donation-button .button:hover {
                    background-color: #005177;
                    border-color: #005177;
                }

                .donation-button .dashicons {
                    margin-right: 8px;
                    font-size: 18px;
                    width: 18px;
                    height: 18px;
                }

                .donation-email {
                    margin-top: 12px;
                    font-size: 12px;
                    color: #646970;
                    font-style: italic;
                }

                /* Responsive Design */
                @media screen and (max-width: 782px) {
                    .wiki-prayer-times-grid {
                        grid-template-columns: 1fr;
                    }
                    .donation-card {
                        grid-column: 1 / 2;
                        margin-top: 20px;
                    }
                    .settings-card {
                        padding: 15px;
                    }
                    .donation-button .button {
                        padding: 6px 16px;
                        font-size: 12px;
                    }
                    .donation-email {
                        font-size: 11px;
                    }
                }
            </style>
        </div>
    <?php
    }

    /**
     * Render section descriptions
     */
    public function render_general_section()
    {
        echo '<p class="section-description">Configure the general settings for the prayer times widget.</p>';
    }

    public function render_display_section()
    {
        echo '<p class="section-description">Customize how the prayer times are displayed.</p>';
    }

    public function render_cache_section()
    {
        echo '<p class="section-description">Configure caching and refresh settings for better performance.</p>';
    }

    public function render_customization_section()
    {
        echo '<p class="section-description">Add custom CSS to style the prayer times widget.</p>';
    }

    /**
     * Render form fields
     */
    public function render_text_field($args)
    {
        $options = get_option('wiki_prayer_times_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    ?>
        <input type="text"
            id="<?php echo esc_attr($args['label_for']); ?>"
            name="wiki_prayer_times_settings[<?php echo esc_attr($args['label_for']); ?>]"
            value="<?php echo esc_attr($value); ?>"
            class="regular-text">
    <?php
    }

    public function render_select_field($args)
    {
        $options = get_option('wiki_prayer_times_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    ?>
        <select id="<?php echo esc_attr($args['label_for']); ?>"
            name="wiki_prayer_times_settings[<?php echo esc_attr($args['label_for']); ?>]">
            <?php foreach ($args['options'] as $key => $label) : ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php
    }

    public function render_checkbox_field($args)
    {
        $options = get_option('wiki_prayer_times_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : true;
    ?>
        <input type="checkbox"
            id="<?php echo esc_attr($args['label_for']); ?>"
            name="wiki_prayer_times_settings[<?php echo esc_attr($args['label_for']); ?>]"
            value="1"
            <?php checked($value, true); ?>>
    <?php
    }

    public function render_number_field($args)
    {
        $options = get_option('wiki_prayer_times_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    ?>
        <input type="number"
            id="<?php echo esc_attr($args['label_for']); ?>"
            name="wiki_prayer_times_settings[<?php echo esc_attr($args['label_for']); ?>]"
            value="<?php echo esc_attr($value); ?>"
            min="<?php echo esc_attr($args['min']); ?>"
            max="<?php echo esc_attr($args['max']); ?>"
            step="<?php echo esc_attr($args['step']); ?>">
    <?php
    }

    public function render_textarea_field($args)
    {
        $options = get_option('wiki_prayer_times_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    ?>
        <textarea id="<?php echo esc_attr($args['label_for']); ?>"
            name="wiki_prayer_times_settings[<?php echo esc_attr($args['label_for']); ?>]"
            rows="5"
            class="large-text code"><?php echo esc_textarea($value); ?></textarea>
<?php
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings($input)
    {
        $sanitized = array();

        // Sanitize text fields
        $sanitized['default_city'] = sanitize_text_field($input['default_city']);
        $sanitized['default_country'] = sanitize_text_field($input['default_country']);
        $sanitized['calculation_method'] = sanitize_text_field($input['calculation_method']);
        $sanitized['time_format'] = sanitize_text_field($input['time_format']);

        // Sanitize numbers
        $sanitized['cache_duration'] = absint($input['cache_duration']);
        $sanitized['refresh_interval'] = absint($input['refresh_interval']);

        // Sanitize checkboxes
        $sanitized['show_hijri_date'] = isset($input['show_hijri_date']);
        $sanitized['show_countdown'] = isset($input['show_countdown']);
        $sanitized['highlight_next_prayer'] = isset($input['highlight_next_prayer']);

        // Sanitize CSS
        $sanitized['custom_css'] = wp_kses_post($input['custom_css']);

        return $sanitized;
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook)
    {
        if ('settings_page_wiki-prayer-times' !== $hook) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery-ui-sortable');
    }
}
