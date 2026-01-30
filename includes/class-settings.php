<?php
/**
 * Plugin Settings Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class PRS_Settings {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_post_prs_reset_settings', array($this, 'handle_reset_settings'));
    }

    /**
     * Handle Reset Settings
     */
    public function handle_reset_settings() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }

        check_admin_referer('prs_reset_action', 'prs_reset_nonce');

        // List of all options to delete
        $options = array(
            'prs_default_template',
            'prs_primary_color',
            'prs_badge_color',
            'prs_heading_color',
            'prs_border_radius',
            'prs_bg_color',
            'prs_text_color',
            'prs_font_family',
            'prs_max_width',
            'prs_price_gradient_1',
            'prs_price_gradient_2',
            'prs_specs_gradient_1',
            'prs_specs_gradient_2',
            'prs_container_padding',
            'prs_box_margin',
            'prs_box_padding',
            'prs_border_width',
            'prs_border_color'
        );

        foreach ($options as $option) {
            delete_option($option);
        }

        // Redirect back with success message
        wp_redirect(add_query_arg(array('page' => 'prs-settings', 'settings-updated' => 'true', 'prs-reset' => 'true'), admin_url('admin.php')));
        exit;
    }

    /**
     * Enqueue Admin Assets
     */
    public function enqueue_admin_assets($hook) {
        if ($hook != 'toplevel_page_prs-settings') {
            return;
        }

        wp_enqueue_style(
            'prs-admin-styles',
            PRS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            PRS_VERSION
        );

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('prs-admin-script', PRS_PLUGIN_URL . 'assets/js/admin.js', array('wp-color-picker'), false, true);
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
            'prs-settings',
            array($this, 'render_settings_page'),
            'dashicons-star-half',
            25
        );
        
        // Explicitly add the first submenu to avoid core title issues
        add_submenu_page(
            'prs-settings',
            $page_title,
            $menu_title,
            'manage_options',
            'prs-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('prs_settings_group', 'prs_default_template', array('sanitize_callback' => 'sanitize_text_field'));
        
        add_settings_section(
            'prs_general_section',
            __('General Settings', 'product-review-system'),
            null,
            'prs-settings'
        );
        
        add_settings_field(
            'prs_default_template',
            __('Global Default Template', 'product-review-system'),
            array($this, 'render_default_template_field'),
            'prs-settings',
            'prs_general_section'
        );

        // Style Settings Section
        add_settings_section(
            'prs_style_section',
            __('Style Settings', 'product-review-system'),
            null,
            'prs-settings'
        );

        // Primary Color
        register_setting('prs_settings_group', 'prs_primary_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_primary_color',
            __('Primary Color', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_style_section',
            array('label_for' => 'prs_primary_color', 'default' => '#667eea')
        );

        // Badge Color
        register_setting('prs_settings_group', 'prs_badge_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_badge_color',
            __('Badge/Gradient Color', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_style_section',
            array('label_for' => 'prs_badge_color', 'default' => '#764ba2')
        );

        // Heading Color
        register_setting('prs_settings_group', 'prs_heading_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_heading_color',
            __('Heading Color', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_style_section',
            array('label_for' => 'prs_heading_color', 'default' => '#333333')
        );

        // Border Radius
        register_setting('prs_settings_group', 'prs_border_radius', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'prs_border_radius',
            __('Border Radius (px)', 'product-review-system'),
            array($this, 'render_number_field'),
            'prs-settings',
            'prs_style_section',
            array('label_for' => 'prs_border_radius', 'default' => '10')
        );

        // --- Typography & Layout Section ---
        add_settings_section(
            'prs_typo_section',
            __('Typography & Layout', 'product-review-system'),
            null,
            'prs-settings'
        );

        // Background Color
        register_setting('prs_settings_group', 'prs_bg_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_bg_color',
            __('Background Color', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_typo_section',
            array('label_for' => 'prs_bg_color', 'default' => '#ffffff')
        );

        // Text Color
        register_setting('prs_settings_group', 'prs_text_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_text_color',
            __('Text Color', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_typo_section',
            array('label_for' => 'prs_text_color', 'default' => '#444444')
        );

        // Font Family
        register_setting('prs_settings_group', 'prs_font_family', array('sanitize_callback' => 'sanitize_text_field'));
        add_settings_field(
            'prs_font_family',
            __('Font Family', 'product-review-system'),
            array($this, 'render_font_field'),
            'prs-settings',
            'prs_typo_section',
            array('label_for' => 'prs_font_family')
        );

        // Max Width
        register_setting('prs_settings_group', 'prs_max_width', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'prs_max_width',
            __('Max Width (px)', 'product-review-system'),
            array($this, 'render_number_field'),
            'prs-settings',
            'prs_typo_section',
            array('label_for' => 'prs_max_width', 'default' => '1200')
        );

        // --- Section Colors Section ---
        add_settings_section(
            'prs_section_colors',
            __('Section Header Colors', 'product-review-system'),
            null,
            'prs-settings'
        );

        // Price Gradient 1
        register_setting('prs_settings_group', 'prs_price_gradient_1', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_price_gradient_1',
            __('Price Header Gradient Start', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_section_colors',
            array('label_for' => 'prs_price_gradient_1', 'default' => '#f093fb')
        );

        // Price Gradient 2
        register_setting('prs_settings_group', 'prs_price_gradient_2', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_price_gradient_2',
            __('Price Header Gradient End', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_section_colors',
            array('label_for' => 'prs_price_gradient_2', 'default' => '#f5576c')
        );

        // Specs Gradient 1
        register_setting('prs_settings_group', 'prs_specs_gradient_1', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_specs_gradient_1',
            __('Specs Header Gradient Start', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_section_colors',
            array('label_for' => 'prs_specs_gradient_1', 'default' => '#4facfe')
        );

        // Specs Gradient 2
        register_setting('prs_settings_group', 'prs_specs_gradient_2', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_specs_gradient_2',
            __('Specs Header Gradient End', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_section_colors',
            array('label_for' => 'prs_specs_gradient_2', 'default' => '#00f2fe')
        );

        // --- Box Model & Borders Section ---
        add_settings_section(
            'prs_box_model_section',
            __('Box Model & Borders', 'product-review-system'),
            null,
            'prs-settings'
        );

        // Container Padding
        register_setting('prs_settings_group', 'prs_container_padding', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'prs_container_padding',
            __('Container Padding (px)', 'product-review-system'),
            array($this, 'render_number_field'),
            'prs-settings',
            'prs_box_model_section',
            array('label_for' => 'prs_container_padding', 'default' => '40')
        );

        // Box Margin
        register_setting('prs_settings_group', 'prs_box_margin', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'prs_box_margin',
            __('Box Margin (px) - Spacing between sections', 'product-review-system'),
            array($this, 'render_number_field'),
            'prs-settings',
            'prs_box_model_section',
            array('label_for' => 'prs_box_margin', 'default' => '30')
        );

        // Box Padding
        register_setting('prs_settings_group', 'prs_box_padding', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'prs_box_padding',
            __('Box Padding (px) - Inner spacing', 'product-review-system'),
            array($this, 'render_number_field'),
            'prs-settings',
            'prs_box_model_section',
            array('label_for' => 'prs_box_padding', 'default' => '30')
        );

        // Border Width
        register_setting('prs_settings_group', 'prs_border_width', array('sanitize_callback' => 'absint'));
        add_settings_field(
            'prs_border_width',
            __('Border Width (px)', 'product-review-system'),
            array($this, 'render_number_field'),
            'prs-settings',
            'prs_box_model_section',
            array('label_for' => 'prs_border_width', 'default' => '0')
        );

        // Border Color
        register_setting('prs_settings_group', 'prs_border_color', array('sanitize_callback' => 'sanitize_hex_color'));
        add_settings_field(
            'prs_border_color',
            __('Border Color', 'product-review-system'),
            array($this, 'render_color_field'),
            'prs-settings',
            'prs_box_model_section',
            array('label_for' => 'prs_border_color', 'default' => '#e9ecef')
        );
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap prs-settings-wrap">
            <h1><?php _e('Product Review Settings', 'product-review-system'); ?></h1>
            
            <?php if (isset($_GET['prs-reset']) && $_GET['prs-reset'] == 'true') : ?>
                <div class="notice notice-info is-dismissible">
                    <p><?php _e('Settings have been reset to defaults.', 'product-review-system'); ?></p>
                </div>
            <?php endif; ?>
            <form method="post" action="options.php"> <!-- Updated Form Action -->
                <?php
                settings_fields('prs_settings_group');
                do_settings_sections('prs-settings');
                submit_button(); ?>
            </form>
            
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="margin-top: -50px; margin-left: 150px; display: inline-block;">
                <input type="hidden" name="action" value="prs_reset_settings">
                <?php wp_nonce_field('prs_reset_action', 'prs_reset_nonce'); ?>
                <?php submit_button(__('Reset to Defaults', 'product-review-system'), 'secondary', 'prs_reset', false, array('onclick' => 'return confirm("' . __('Are you sure you want to reset all settings?', 'product-review-system') . '");')); ?>
            </form>
            <div style="clear:both;"></div>
        </div>
        <?php
    }
    
    /**
     * Render default template field
     */
    public function render_default_template_field() {
        $default_template = get_option('prs_default_template', 'default');
        
        $templates = array(
            'default' => __('WordPress Default', 'product-review-system'),
            'single-product-review.php' => __('Product Review Template', 'product-review-system'),
            'single-comparison.php' => __('Product Comparison Template', 'product-review-system'),
            'single-minimal.php' => __('Minimal Review Template', 'product-review-system')
        );
        ?>
        <select name="prs_default_template">
            <?php foreach ($templates as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($default_template, $value); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php _e('This template will be used if "Default Template" is selected on the post.', 'product-review-system'); ?></p>
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
        <input type="text" name="<?php echo esc_attr($option_name); ?>" value="<?php echo esc_attr($value); ?>" class="prs-color-field" data-default-color="<?php echo esc_attr($default); ?>">
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

new PRS_Settings();
