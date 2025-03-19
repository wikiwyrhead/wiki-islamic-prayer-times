<?php

/**
 * Prayer Times Settings Class
 *
 * @package Wiki_Islamic_Prayer_Times
 */

if (!defined('ABSPATH')) {
    exit;
}

class Prayer_Times_Settings
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
            'Prayer Times Widget Settings',
            'Prayer Times Widget',
            'manage_options',
            'prayer-times-widget',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings()
    {
        register_setting('ptw_settings', 'ptw_settings', array($this, 'sanitize_settings'));

        // General Settings Section
        add_settings_section(
            'ptw_general_section',
            'General Settings',
            array($this, 'render_general_section'),
            'prayer-times-widget'
        );

        // Display Settings Section
        add_settings_section(
            'ptw_display_section',
            'Display Settings',
            array($this, 'render_display_section'),
            'prayer-times-widget'
        );

        // Cache Settings Section
        add_settings_section(
            'ptw_cache_section',
            'Cache Settings',
            array($this, 'render_cache_section'),
            'prayer-times-widget'
        );

        // Customization Section
        add_settings_section(
            'ptw_customization_section',
            'Customization',
            array($this, 'render_customization_section'),
            'prayer-times-widget'
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
            'prayer-times-widget',
            'ptw_general_section',
            array('label_for' => 'default_city')
        );

        add_settings_field(
            'default_country',
            'Default Country',
            array($this, 'render_text_field'),
            'prayer-times-widget',
            'ptw_general_section',
            array('label_for' => 'default_country')
        );

        add_settings_field(
            'calculation_method',
            'Calculation Method',
            array($this, 'render_select_field'),
            'prayer-times-widget',
            'ptw_general_section',
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
            'prayer-times-widget',
            'ptw_display_section',
            array('label_for' => 'show_hijri_date')
        );

        add_settings_field(
            'show_countdown',
            'Show Countdown',
            array($this, 'render_checkbox_field'),
            'prayer-times-widget',
            'ptw_display_section',
            array('label_for' => 'show_countdown')
        );

        add_settings_field(
            'time_format',
            'Time Format',
            array($this, 'render_select_field'),
            'prayer-times-widget',
            'ptw_display_section',
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
            'prayer-times-widget',
            'ptw_display_section',
            array('label_for' => 'highlight_next_prayer')
        );

        // Cache Settings Fields
        add_settings_field(
            'cache_duration',
            'Cache Duration (seconds)',
            array($this, 'render_number_field'),
            'prayer-times-widget',
            'ptw_cache_section',
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
            'prayer-times-widget',
            'ptw_cache_section',
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
            'prayer-times-widget',
            'ptw_customization_section',
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
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('ptw_settings');
                do_settings_sections('prayer-times-widget');
                submit_button('Save Settings');
                ?>
            </form>
        </div>
    <?php
    }

    /**
     * Render section descriptions
     */
    public function render_general_section()
    {
        echo '<p>Configure the general settings for the prayer times widget.</p>';
    }

    public function render_display_section()
    {
        echo '<p>Customize how the prayer times are displayed.</p>';
    }

    public function render_cache_section()
    {
        echo '<p>Configure caching and refresh settings for better performance.</p>';
    }

    public function render_customization_section()
    {
        echo '<p>Add custom CSS to style the prayer times widget.</p>';
    }

    /**
     * Render form fields
     */
    public function render_text_field($args)
    {
        $options = get_option('ptw_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    ?>
        <input type="text"
            id="<?php echo esc_attr($args['label_for']); ?>"
            name="ptw_settings[<?php echo esc_attr($args['label_for']); ?>]"
            value="<?php echo esc_attr($value); ?>"
            class="regular-text">
    <?php
    }

    public function render_select_field($args)
    {
        $options = get_option('ptw_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    ?>
        <select id="<?php echo esc_attr($args['label_for']); ?>"
            name="ptw_settings[<?php echo esc_attr($args['label_for']); ?>]">
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
        $options = get_option('ptw_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : true;
    ?>
        <input type="checkbox"
            id="<?php echo esc_attr($args['label_for']); ?>"
            name="ptw_settings[<?php echo esc_attr($args['label_for']); ?>]"
            value="1"
            <?php checked($value, true); ?>>
    <?php
    }

    public function render_number_field($args)
    {
        $options = get_option('ptw_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    ?>
        <input type="number"
            id="<?php echo esc_attr($args['label_for']); ?>"
            name="ptw_settings[<?php echo esc_attr($args['label_for']); ?>]"
            value="<?php echo esc_attr($value); ?>"
            min="<?php echo esc_attr($args['min']); ?>"
            max="<?php echo esc_attr($args['max']); ?>"
            step="<?php echo esc_attr($args['step']); ?>">
    <?php
    }

    public function render_textarea_field($args)
    {
        $options = get_option('ptw_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    ?>
        <textarea id="<?php echo esc_attr($args['label_for']); ?>"
            name="ptw_settings[<?php echo esc_attr($args['label_for']); ?>]"
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
        if ('settings_page_prayer-times-widget' !== $hook) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery-ui-sortable');
    }
}
