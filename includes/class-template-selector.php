<?php
/**
 * Plugin Name: Custom Post Template Selector
 * Description: Enables custom single post templates with dropdown selector
 * Version: 1.0
 */

// Add custom templates to template dropdown
function add_custom_post_templates($templates) {
    $templates['single-product-review.php'] = __('Product Review Template', 'product-review-system');
    $templates['single-comparison.php'] = __('Product Comparison Template', 'product-review-system');
    $templates['single-minimal.php'] = __('Minimal Review Template', 'product-review-system');
    return $templates;
}
add_filter('theme_post_templates', 'add_custom_post_templates');

// Load custom template
function load_custom_post_template($template) {
    global $post;
    
    if (!$post) {
        return $template;
    }
    
    // Get the custom template from post meta
    $custom_template = get_post_meta($post->ID, '_wp_page_template', true);
    
    // Fallback to global default if template is default or empty
    if (empty($custom_template) || $custom_template == 'default') {
        global $prs_data;
        if (isset($prs_data['prs_default_template'])) {
            $custom_template = $prs_data['prs_default_template'];
        } else {
            // Fallback for when Redux is not active or data not saved
            $custom_template = get_option('prs_default_template', 'default');
        }
    }
    
    if ($custom_template && $custom_template != 'default') {
        // Check in plugin directory first
        $plugin_template = plugin_dir_path(dirname(__FILE__)) . 'templates/' . $custom_template;
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
        
        // Check in theme directory
        $theme_template = get_stylesheet_directory() . '/' . $custom_template;
        if (file_exists($theme_template)) {
            return $theme_template;
        }
    }
    
    return $template;
}
add_filter('single_template', 'load_custom_post_template');

// Add template selector meta box to post editor
function add_template_selector_meta_box() {
    add_meta_box(
        'post_template_selector',
        __('Post Template', 'product-review-system'),
        'render_template_selector_meta_box',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_template_selector_meta_box');

// Render template selector
function render_template_selector_meta_box($post) {
    wp_nonce_field('save_post_template', 'post_template_nonce');
    
    $current_template = get_post_meta($post->ID, '_wp_page_template', true);
    if (empty($current_template)) {
        $current_template = 'default';
    }
    
    $templates = array(
        'default' => __('Default Template', 'product-review-system'),
        'single-product-review.php' => __('Product Review Template', 'product-review-system'),
        'single-comparison.php' => __('Product Comparison Template', 'product-review-system'),
        'single-minimal.php' => __('Minimal Review Template', 'product-review-system')
    );
    
    ?>
    <p>
        <label for="page_template"><strong><?php _e('Select Template:', 'product-review-system'); ?></strong></label>
        <select name="page_template" id="page_template" class="widefat">
            <?php foreach ($templates as $template_file => $template_name) : ?>
                <option value="<?php echo esc_attr($template_file); ?>" <?php selected($current_template, $template_file); ?>>
                    <?php echo esc_html($template_name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p class="description"><?php _e('Choose a custom template for this post.', 'product-review-system'); ?></p>
    <?php
}

// Save template selection
function save_post_template_selection($post_id) {
    // Check nonce
    if (!isset($_POST['post_template_nonce']) || !wp_verify_nonce($_POST['post_template_nonce'], 'save_post_template')) {
        return $post_id;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    // Save template
    if (isset($_POST['page_template'])) {
        update_post_meta($post_id, '_wp_page_template', sanitize_text_field($_POST['page_template']));
    }
}
add_action('save_post', 'save_post_template_selection');

// Add admin notice for template location
function custom_template_admin_notice() {
    $screen = get_current_screen();
    if ($screen->id === 'post') {
        ?>
        <div class="notice notice-info is-dismissible">
            <p><strong><?php _e('Custom Templates:', 'product-review-system'); ?></strong> <?php _e('Place your custom template files in either:', 'product-review-system'); ?></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><code><?php echo esc_html(plugin_dir_path(__FILE__)); ?></code> (<?php _e('Plugin folder', 'product-review-system'); ?>)</li>
                <li><code><?php echo esc_html(get_stylesheet_directory()); ?>/</code> (<?php _e('Theme folder', 'product-review-system'); ?>)</li>
            </ul>
        </div>
        <?php
    }
}
add_action('admin_notices', 'custom_template_admin_notice');
