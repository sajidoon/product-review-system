<?php
/**
 * ReduxFramework Config File
 * For Advanced Product Review System
 */
 if ( ! defined( 'ABSPATH' ) ) exit;
if (!class_exists('Redux')) {
    return;
}

$opt_name = 'prs_data';

$theme = wp_get_theme();

$args = array(
    'opt_name'             => $opt_name,
    'display_name'         => 'Advanced Product Review System',
    'display_version'      => PRS_VERSION,
    'menu_type'            => 'menu',
    'allow_sub_menu'       => true,
    'menu_title'           => 'Review Settings',
    'page_title'           => 'Review Settings',
    'google_api_key'       => '',
    'google_update_weekly' => false,
    'async_typography'     => true,
    'admin_bar'            => true,
    'admin_bar_icon'       => 'dashicons-star-half',
    'admin_bar_priority'   => 50,
    'global_variable'      => $opt_name,
    'dev_mode'             => false,
    'update_notice'        => false,
    'customizer'           => true,
    'page_priority'        => 2,
    // 'page_parent'          => 'options-general.php',
    'page_permissions'     => 'manage_options',
    'menu_icon'            => '',
    'last_tab'             => '',
    'page_icon'            => 'icon-themes',
    'page_slug'            => 'prs-settings',
    'save_defaults'        => true,
    'default_show'         => false,
    'default_mark'         => '',
    'show_import_export'   => true,
);

Redux::setArgs($opt_name, $args);

// -> START General Fields
Redux::setSection($opt_name, array(
    'title'  =>  esc_html_e('General Settings', 'advanced-product-review-system'),
    'id'     => 'general',
    'desc'   =>  esc_html_e('General configuration for the review system.', 'advanced-product-review-system'),
    'icon'   => 'el el-cogs',
    'fields' => array(
        array(
            'id'       => 'prs_default_template',
            'type'     => 'select',
            'title'    =>  esc_html_e('Global Default Template', 'advanced-product-review-system'),
            'subtitle' =>  esc_html_e('Select the default template for reviews.', 'advanced-product-review-system'),
            'options'  => array(
                'default'               => 'WordPress Default',
                'single-product-review.php' => 'Product Review Template',
                'single-comparison.php'     => 'Product Comparison Template',
                'single-minimal.php'        => 'Minimal Review Template'
            ),
            'default'  => 'default',
        ),
    ),
));

// -> START Style Settings
Redux::setSection($opt_name, array(
    'title' =>  esc_html_e('Style Settings', 'advanced-product-review-system'),
    'id'    => 'styling',
    'desc'  =>  esc_html_e('Customize the look and feel.', 'advanced-product-review-system'),
    'icon'  => 'el el-brush',
    'fields' => array(
        array(
            'id'       => 'prs_primary_color',
            'type'     => 'color',
            'title'    =>  esc_html_e('Primary Color', 'advanced-product-review-system'),
            'subtitle' =>  esc_html_e('Used for buttons, progress bars, and links.', 'advanced-product-review-system'),
            'default'  => '#667eea',
            'validate' => 'color',
        ),
        array(
            'id'       => 'prs_badge_color',
            'type'     => 'color',
            'title'    =>  esc_html_e('Badge/Gradient Color', 'advanced-product-review-system'),
            'subtitle' =>  esc_html_e('Secondary color for gradients and badges.', 'advanced-product-review-system'),
            'default'  => '#764ba2',
            'validate' => 'color',
        ),
        array(
            'id'       => 'prs_heading_color',
            'type'     => 'color',
            'title'    =>  esc_html_e('Heading Color', 'advanced-product-review-system'),
            'default'  => '#333333',
            'validate' => 'color',
        ),
        array(
            'id'       => 'prs_bg_color',
            'type'     => 'color',
            'title'    =>  esc_html_e('Background Color', 'advanced-product-review-system'),
            'default'  => '#ffffff',
            'validate' => 'color',
        ),
        array(
            'id'       => 'prs_text_color',
            'type'     => 'color',
            'title'    =>  esc_html_e('Text Color', 'advanced-product-review-system'),
            'default'  => '#444444',
            'validate' => 'color',
        ),
        array(
            'id'       => 'prs_font_family',
            'type'     => 'select',
            'title'    =>  esc_html_e('Font Family', 'advanced-product-review-system'),
            'options'  => array(
                'inherit' => 'Default (Theme Font)',
                'Arial, sans-serif' => 'Arial',
                'Helvetica, sans-serif' => 'Helvetica',
                'Times New Roman, serif' => 'Times New Roman',
                'Georgia, serif' => 'Georgia',
                'Courier New, monospace' => 'Courier New',
                'Verdana, sans-serif' => 'Verdana',
                'Tahoma, sans-serif' => 'Tahoma'
            ),
            'default'  => 'inherit',
        ),
        array(
            'id'       => 'prs_max_width',
            'type'     => 'slider',
            'title'    =>  esc_html_e('Container Max Width (px)', 'advanced-product-review-system'),
            'min'      => 600,
            'max'      => 1600,
            'step'     => 10,
            'default'  => 1200,
        ),
    ),
));

// -> START Section Colors
Redux::setSection($opt_name, array(
    'title' =>  esc_html_e('Section Header Colors', 'advanced-product-review-system'),
    'id'    => 'section_colors',
    'icon'  => 'el el-tint',
    'fields' => array(
        array(
            'id'       => 'prs_price_gradient_1',
            'type'     => 'color',
            'title'    =>  esc_html_e('Price Header Gradient Start', 'advanced-product-review-system'),
            'default'  => '#f093fb',
            'validate' => 'color',
        ),
        array(
            'id'       => 'prs_price_gradient_2',
            'type'     => 'color',
            'title'    =>  esc_html_e('Price Header Gradient End', 'advanced-product-review-system'),
            'default'  => '#f5576c',
            'validate' => 'color',
        ),
        array(
            'id'       => 'prs_specs_gradient_1',
            'type'     => 'color',
            'title'    =>  esc_html_e('Specs Header Gradient Start', 'advanced-product-review-system'),
            'default'  => '#4facfe',
            'validate' => 'color',
        ),
        array(
            'id'       => 'prs_specs_gradient_2',
            'type'     => 'color',
            'title'    =>  esc_html_e('Specs Header Gradient End', 'advanced-product-review-system'),
            'default'  => '#00f2fe',
            'validate' => 'color',
        ),
    ),
));

// -> START Box Model
Redux::setSection($opt_name, array(
    'title' =>  esc_html_e('Box Model & Borders', 'advanced-product-review-system'),
    'id'    => 'box_model',
    'icon'  => 'el el-resize-full',
    'fields' => array(
        array(
            'id'       => 'prs_container_padding',
            'type'     => 'spinner',
            'title'    =>  esc_html_e('Container Padding (px)', 'advanced-product-review-system'),
            'default'  => '40',
            'min'      => 0,
            'max'      => 200,
            'step'     => 5,
        ),
        array(
            'id'       => 'prs_box_margin',
            'type'     => 'spinner',
            'title'    =>  esc_html_e('Box Margin (px)', 'advanced-product-review-system'),
            'subtitle' =>  esc_html_e('Spacing between sections', 'advanced-product-review-system'),
            'default'  => '30',
            'min'      => 0,
            'max'      => 100,
            'step'     => 5,
        ),
        array(
            'id'       => 'prs_box_padding',
            'type'     => 'spinner',
            'title'    =>  esc_html_e('Box Padding (px)', 'advanced-product-review-system'),
            'subtitle' =>  esc_html_e('Inner spacing', 'advanced-product-review-system'),
            'default'  => '30',
            'min'      => 0,
            'max'      => 100,
            'step'     => 5,
        ),
        array(
            'id'       => 'prs_border_width',
            'type'     => 'spinner',
            'title'    =>  esc_html_e('Border Width (px)', 'advanced-product-review-system'),
            'default'  => '0',
            'min'      => 0,
            'max'      => 20,
            'step'     => 1,
        ),
        array(
            'id'       => 'prs_border_radius',
            'type'     => 'spinner',
            'title'    =>  esc_html_e('Border Radius (px)', 'advanced-product-review-system'),
            'default'  => '10',
            'min'      => 0,
            'max'      => 100,
            'step'     => 1,
        ),
        array(
            'id'       => 'prs_border_color',
            'type'     => 'color',
            'title'    =>  esc_html_e('Border Color', 'advanced-product-review-system'),
            'default'  => '#e9ecef',
            'validate' => 'color',
        ),
    ),
));
