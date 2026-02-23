<?php
/**
 * Plugin Settings Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class APRS_Settings {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_post_aprs_reset_settings', array($this, 'handle_reset_settings'));
    }

    /**
     * Handle Reset Settings
     */
    public function handle_reset_settings() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }

        check_admin_referer('aprs_reset_action', 'aprs_reset_nonce');

        // List of all options to delete
        $options = array(
            'aprs_default_template',
            'aprs_primary_color',
            'aprs_badge_color',
            'aprs_heading_color',
            'aprs_border_radius',
            'aprs_bg_color',
            'aprs_text_color',
            'aprs_font_family',
            'aprs_max_width',
            'aprs_price_gradient_1',
            'aprs_price_gradient_2',
            'aprs_specs_gradient_1',
            'aprs_specs_gradient_2',
            'aprs_container_padding',
            'aprs_box_margin',
            'aprs_box_padding',
            'aprs_border_width',
            'aprs_border_color'
        );

        foreach ($options as $option) {
            delete_option($option);
        }

        // Redirect back with success message
        wp_safe_redirect(add_query_arg(array('page' => 'aprs-settings', 'settings-updated' => 'true', 'aprs-reset' => 'true'), admin_url('admin.php'))); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        exit;
    }

    /**
     * Enqueue Admin Assets
     */
    public function enqueue_admin_assets($hook) {
        if ($hook != 'toplevel_page_aprs-settings') {
            return;
        }

        wp_enqueue_style(
            'aprs-admin-styles',
            APRS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            APRS_VERSION
        );

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('aprs-admin-script', APRS_PLUGIN_URL . 'assets/js/admin.js', array('wp-color-picker'), APRS_VERSION, true);
    }
    
    /**
     * Add settings page
     */
    public function add_settings_page() {
        $page_title = 'Product Review Settings';
        $menu_title = 'Review Settings';
        
        add_menu_page(
            $page_title,
            $menu_title,
            'manage_options',
            'aprs-settings',
            array($this, 'render_settings_page'),
            'dashicons-star-half',
            25
        );
        
        // Explicitly add the first submenu to avoid core title issues
        add_submenu_page(
            'aprs-settings',
            $page_title,
            $menu_title,
            'manage_options',
            'aprs-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('aprs_settings_group', 'aprs_default_template', array('sanitize_callback' => 'sanitize_text_field'));
        
        add_settings_section(
            'aprs_general_section',
            __('General Settings', 'advanced-product-review-system'),
            null,
            'aprs-settings'
        );
        
        add_settings_field(
            'aprs_default_template',
            __('Global Default Template', 'advanced-product-review-system'),
            array($this, 'render_default_template_field'),
            'aprs-settings',
            'aprs_general_section'
        );

        // Style Settings Section
        add_settings_section(
            'aprs_style_section',
            __('Style Settings', 'advanced-product-review-system'),
            null,
            'aprs-settings'
        );

        // Primary Color
        register_setting('aprs_settings_group', 'aprs_primary_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_primary_color',
            __('Primary Color', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_style_section',
            array('label_for' => 'aprs_primary_color', 'default' => '#667eea')
        );

        // Badge Color
        register_setting('aprs_settings_group', 'aprs_badge_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_badge_color',
            __('Badge/Gradient Color', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_style_section',
            array('label_for' => 'aprs_badge_color', 'default' => '#764ba2')
        );

        // Heading Color
        register_setting('aprs_settings_group', 'aprs_heading_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_heading_color',
            __('Heading Color', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_style_section',
            array('label_for' => 'aprs_heading_color', 'default' => '#333333')
        );

        // Border Radius
        register_setting('aprs_settings_group', 'aprs_border_radius', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'aprs_border_radius',
            __('Border Radius (px)', 'advanced-product-review-system'),
            array($this, 'render_number_field'),
            'aprs-settings',
            'aprs_style_section',
            array('label_for' => 'aprs_border_radius', 'default' => '10')
        );

        // --- Typography & Layout Section ---
        add_settings_section(
            'aprs_typo_section',
            __('Typography & Layout', 'advanced-product-review-system'),
            null,
            'aprs-settings'
        );

        // Background Color
        register_setting('aprs_settings_group', 'aprs_bg_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_bg_color',
            __('Background Color', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_typo_section',
            array('label_for' => 'aprs_bg_color', 'default' => '#ffffff')
        );

        // Text Color
        register_setting('aprs_settings_group', 'aprs_text_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_text_color',
            __('Text Color', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_typo_section',
            array('label_for' => 'aprs_text_color', 'default' => '#444444')
        );

        // Font Family
        register_setting('aprs_settings_group', 'aprs_font_family', array('sanitize_callback' => 'sanitize_text_field'));
        add_settings_field(
            'aprs_font_family',
            __('Font Family', 'advanced-product-review-system'),
            array($this, 'render_font_field'),
            'aprs-settings',
            'aprs_typo_section',
            array('label_for' => 'aprs_font_family')
        );

        // Max Width
        register_setting('aprs_settings_group', 'aprs_max_width', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'aprs_max_width',
            __('Max Width (px)', 'advanced-product-review-system'),
            array($this, 'render_number_field'),
            'aprs-settings',
            'aprs_typo_section',
            array('label_for' => 'aprs_max_width', 'default' => '1200')
        );

        // --- Section Colors Section ---
        add_settings_section(
            'aprs_section_colors',
            __('Section Header Colors', 'advanced-product-review-system'),
            null,
            'aprs-settings'
        );

        // Price Gradient 1
        register_setting('aprs_settings_group', 'aprs_price_gradient_1', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_price_gradient_1',
            __('Price Header Gradient Start', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_section_colors',
            array('label_for' => 'aprs_price_gradient_1', 'default' => '#f093fb')
        );

        // Price Gradient 2
        register_setting('aprs_settings_group', 'aprs_price_gradient_2', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_price_gradient_2',
            __('Price Header Gradient End', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_section_colors',
            array('label_for' => 'aprs_price_gradient_2', 'default' => '#f5576c')
        );

        // Specs Gradient 1
        register_setting('aprs_settings_group', 'aprs_specs_gradient_1', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_specs_gradient_1',
            __('Specs Header Gradient Start', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_section_colors',
            array('label_for' => 'aprs_specs_gradient_1', 'default' => '#4facfe')
        );

        // Specs Gradient 2
        register_setting('aprs_settings_group', 'aprs_specs_gradient_2', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_specs_gradient_2',
            __('Specs Header Gradient End', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_section_colors',
            array('label_for' => 'aprs_specs_gradient_2', 'default' => '#00f2fe')
        );

        // --- Box Model & Borders Section ---
        add_settings_section(
            'aprs_box_model_section',
            __('Box Model & Borders', 'advanced-product-review-system'),
            null,
            'aprs-settings'
        );

        // Container Padding
        register_setting('aprs_settings_group', 'aprs_container_padding', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'aprs_container_padding',
            __('Container Padding (px)', 'advanced-product-review-system'),
            array($this, 'render_number_field'),
            'aprs-settings',
            'aprs_box_model_section',
            array('label_for' => 'aprs_container_padding', 'default' => '40')
        );

        // Box Margin
        register_setting('aprs_settings_group', 'aprs_box_margin', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'aprs_box_margin',
            __('Box Margin (px) - Spacing between sections', 'advanced-product-review-system'),
            array($this, 'render_number_field'),
            'aprs-settings',
            'aprs_box_model_section',
            array('label_for' => 'aprs_box_margin', 'default' => '30')
        );

        // Box Padding
        register_setting('aprs_settings_group', 'aprs_box_padding', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'aprs_box_padding',
            __('Box Padding (px) - Inner spacing', 'advanced-product-review-system'),
            array($this, 'render_number_field'),
            'aprs-settings',
            'aprs_box_model_section',
            array('label_for' => 'aprs_box_padding', 'default' => '30')
        );

        // Border Width
        register_setting('aprs_settings_group', 'aprs_border_width', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'aprs_border_width',
            __('Border Width (px)', 'advanced-product-review-system'),
            array($this, 'render_number_field'),
            'aprs-settings',
            'aprs_box_model_section',
            array('label_for' => 'aprs_border_width', 'default' => '0')
        );

        // Border Color
        register_setting('aprs_settings_group', 'aprs_border_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'aprs_border_color',
            __('Border Color', 'advanced-product-review-system'),
            array($this, 'render_color_field'),
            'aprs-settings',
            'aprs_box_model_section',
            array('label_for' => 'aprs_border_color', 'default' => '#e9ecef')
        );
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap aprs-settings-wrap">
            <h1><?php esc_html_e('Product Review Settings', 'advanced-product-review-system'); ?></h1>
            
            <?php // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- UI notice only
            if (isset($_GET['aprs-reset']) && $_GET['aprs-reset'] == 'true') : ?>
                <div class="notice notice-info is-dismissible">
                    <p><?php esc_html_e('Settings have been reset to defaults.', 'advanced-product-review-system'); ?></p>
                </div>
            <?php endif; ?>
            <form method="post" action="options.php"> <!-- Updated Form Action -->
                <?php
                settings_fields('aprs_settings_group');
                do_settings_sections('aprs-settings');
                submit_button(); ?>
            </form>
            
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-top: -50px; margin-left: 150px; display: inline-block;">
                <input type="hidden" name="action" value="aprs_reset_settings">
                <?php wp_nonce_field('aprs_reset_action', 'aprs_reset_nonce'); ?>
                <?php submit_button(__('Reset to Defaults', 'advanced-product-review-system'), 'secondary', 'aprs_reset', false, array('onclick' => 'return confirm("' . __('Are you sure you want to reset all settings?', 'advanced-product-review-system') . '");')); ?>
            </form>
            <div style="clear:both;"></div>
        </div>
        <?php
    }
    
    /**
     * Render default template field
     */
    public function render_default_template_field() {
        $default_template = get_option('aprs_default_template', 'default');
        
        $templates = array(
            'default' => __('WordPress Default', 'advanced-product-review-system'),
            'single-product-review.php' => __('Product Review Template', 'advanced-product-review-system'),
            'single-comparison.php' => __('Product Comparison Template', 'advanced-product-review-system'),
            'single-minimal.php' => __('Minimal Review Template', 'advanced-product-review-system')
        );
        ?>
        <select name="aprs_default_template">
            <?php foreach ($templates as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($default_template, $value); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e('This template will be used if "Default Template" is selected on the post.', 'advanced-product-review-system'); ?></p>
        <?php
    }

    /**
     * Render Color Field
     */
    public function render_color_field($args) {
        $option_name = $args['label_for'];
        $default = isset($args['default']) ? $args['default'] : '#000000';
        $value = get_option($option_name, $default);
        ?>
        <input type="text" name="<?php echo esc_attr($option_name); ?>" value="<?php echo esc_attr($value); ?>" class="aprs-color-field" data-default-color="<?php echo esc_attr($default); ?>">
        <?php
    }

    /**
     * Render Number Field
     */
    public function render_number_field($args) {
        $option_name = $args['label_for'];
        $default = isset($args['default']) ? $args['default'] : '0';
        $value = get_option($option_name, $default);
        ?>
        <input type="number" name="<?php echo esc_attr($option_name); ?>" value="<?php echo esc_attr($value); ?>" class="small-text"> px
        <?php
    }

    /**
     * Render Font Field
     */
    public function render_font_field($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 'inherit');
        $fonts = array(
            'inherit' => 'Default (Theme Font)',
            'Arial, sans-serif' => 'Arial',
            'Helvetica, sans-serif' => 'Helvetica',
            'Times New Roman, serif' => 'Times New Roman',
            'Georgia, serif' => 'Georgia',
            'Courier New, monospace' => 'Courier New',
            'Verdana, sans-serif' => 'Verdana',
            'Tahoma, sans-serif' => 'Tahoma'
        );
        ?>
        <select name="<?php echo esc_attr($option_name); ?>">
            <?php foreach ($fonts as $font_val => $font_label) : ?>
                <option value="<?php echo esc_attr($font_val); ?>" <?php selected($value, $font_val); ?>>
                    <?php echo esc_html($font_label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }
}

new APRS_Settings();
