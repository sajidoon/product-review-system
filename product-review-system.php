<?php
/**
 * Plugin Name: Advanced Product Review System
 * Description: Complete product review system with ratings, price comparison, specifications, and custom templates
 * Version: 2.1.0
 * Author: Sajid Iqbal
 * Author URI: https://www.linkedin.com/in/muhammad-sajid-iqbal-7bb56a1a1/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: advanced-product-review-system
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('APRS_VERSION', '2.1.0');
define('APRS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('APRS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('APRS_PLUGIN_FILE', __FILE__);

/**
 * Main Plugin Class
 */
class APRS_Advanced_Product_Review_System {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init();
    }
    
    /**
     * Initialize plugin
     */
    private function init() {


        // Load components
        require_once APRS_PLUGIN_DIR . 'includes/class-meta-boxes.php';
        require_once APRS_PLUGIN_DIR . 'includes/class-frontend-display.php';
        require_once APRS_PLUGIN_DIR . 'includes/class-template-selector.php';
        
        // Load Settings
        if (file_exists(APRS_PLUGIN_DIR . 'includes/class-settings.php')) {
            require_once APRS_PLUGIN_DIR . 'includes/class-settings.php';
        }
        
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        
        // Output dynamic styles
        add_action('wp_head', array($this, 'output_dynamic_css'));
        
        // Activation hook
        register_activation_hook(APRS_PLUGIN_FILE, array($this, 'activate'));
        
        // Deactivation hook
        register_deactivation_hook(APRS_PLUGIN_FILE, array($this, 'deactivate'));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        if (is_single()) {
            // CSS
            wp_enqueue_style(
                'aprs-styles',
                APRS_PLUGIN_URL . 'assets/css/frontend.css',
                array(),
                APRS_VERSION
            );
            
            // JavaScript (with animation)
            wp_enqueue_script(
                'aprs-scripts',
                APRS_PLUGIN_URL . 'assets/js/frontend.js',
                array('jquery'),
                APRS_VERSION,
                true
            );
        }
    }
    
    /**
     * Output Dynamic CSS Variables
     */
    public function output_dynamic_css() {
        if (!is_single()) return;
        
        // Use get_option for native settings
        $primary_color = get_option('aprs_primary_color', '#667eea');
        $badge_color = get_option('aprs_badge_color', '#764ba2');
        $heading_color = get_option('aprs_heading_color', '#333333');
        $border_radius = get_option('aprs_border_radius', '10');
        
        // Extended Settings
        $bg_color = get_option('aprs_bg_color', '#ffffff');
        $text_color = get_option('aprs_text_color', '#444444');
        $font_family = get_option('aprs_font_family', 'inherit');
        $max_width = get_option('aprs_max_width', '1200');
        
        $price_grad_1 = get_option('aprs_price_gradient_1', '#f093fb');
        $price_grad_2 = get_option('aprs_price_gradient_2', '#f5576c');
        $specs_grad_1 = get_option('aprs_specs_gradient_1', '#4facfe');
        $specs_grad_2 = get_option('aprs_specs_gradient_2', '#00f2fe');
        
        // Box Model Settings
        $container_padding = get_option('aprs_container_padding', '40');
        $box_margin = get_option('aprs_box_margin', '30');
        $box_padding = get_option('aprs_box_padding', '30');
        $border_width = get_option('aprs_border_width', '0');
        $border_color = get_option('aprs_border_color', '#e9ecef');
        
        ?>
        <style type="text/css">
            :root {
                --aprs-primary-color: <?php echo esc_attr($primary_color); ?>;
                --aprs-badge-color: <?php echo esc_attr($badge_color); ?>;
                --aprs-heading-color: <?php echo esc_attr($heading_color); ?>;
                --aprs-border-radius: <?php echo esc_attr($border_radius); ?>px;
                
                --aprs-bg-color: <?php echo esc_attr($bg_color); ?>;
                --aprs-text-color: <?php echo esc_attr($text_color); ?>;
                --aprs-font-family: <?php echo esc_html($font_family); ?>;
                --aprs-max-width: <?php echo esc_attr($max_width); ?>px;
                
                --aprs-price-grad-1: <?php echo esc_attr($price_grad_1); ?>;
                --aprs-price-grad-2: <?php echo esc_attr($price_grad_2); ?>;
                --aprs-specs-grad-1: <?php echo esc_attr($specs_grad_1); ?>;
                --aprs-specs-grad-2: <?php echo esc_attr($specs_grad_2); ?>;
                
                --aprs-container-padding: <?php echo esc_attr($container_padding); ?>px;
                --aprs-box-margin: <?php echo esc_attr($box_margin); ?>px;
                --aprs-box-padding: <?php echo esc_attr($box_padding); ?>px;
                --aprs-border-width: <?php echo esc_attr($border_width); ?>px;
                --aprs-border-color: <?php echo esc_attr($border_color); ?>;
            }
        </style>
        <?php
    }

    /**
     * Activation
     */
    public function activate() {
        // Create necessary database tables if needed
        // Set default options
        flush_rewrite_rules();
    }
    
    /**
     * Deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Initialize plugin
function aprs_init() {
    return APRS_Advanced_Product_Review_System::get_instance();
}

// Start the plugin
add_action('plugins_loaded', 'aprs_init');

/**
 * Helper Functions
 */

// Get overall rating
function aprs_get_rating($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_overall_rating', true);
}

// Get product ratings
function aprs_get_ratings($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_ratings', true);
}

// Get product features
function aprs_get_features($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_features', true);
}

// Get product prices
function aprs_get_prices($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_product_prices', true);
}

// Get pros
function aprs_get_pros($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_pros', true);
}

// Get cons
function aprs_get_cons($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_cons', true);
}

// Check if editor's choice
function aprs_is_editors_choice($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_editor_choice', true) === '1';
}
