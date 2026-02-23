<?php
/**
 * Frontend Display Class
 * Handles all frontend output
 */

if (!defined('ABSPATH')) {
    exit;
}

class APRS_Frontend_Display {
    
    public function __construct() {
        add_filter('the_content', array($this, 'display_product_info'));
    }
    
    /**
     * Display product information on frontend
     */
    public function display_product_info($content) {
        if (!is_single()) {
            return $content;
        }

        $post_id = get_the_ID();
        $overall_rating = get_post_meta($post_id, '_overall_rating', true);
        $editor_choice = get_post_meta($post_id, '_editor_choice', true);
        $ratings = get_post_meta($post_id, '_ratings', true);
        $pros = get_post_meta($post_id, '_pros', true);
        $cons = get_post_meta($post_id, '_cons', true);
        $features = get_post_meta($post_id, '_features', true);
        $product_prices = get_post_meta($post_id, '_product_prices', true);

        $output = '';

        // Overall Score Box
        if (!empty($overall_rating)) {
            $output .= '<div class="review-score-box">';
            if ($editor_choice) {
                $output .= '<div class="editors-choice-badge">' . esc_html__('Editor\'s Choice', 'advanced-product-review-system') . '</div>';
            }
            $output .= '<div class="overall-score">';
            $output .= '<span class="score-number">' . esc_html($overall_rating) . '</span>';
            $output .= '<span class="score-label">' . esc_html__('Overall Score', 'advanced-product-review-system') . '</span>';
            $output .= '</div>';
            $output .= '</div>';
        }

        // Detailed Ratings
        if (!empty($ratings)) {
            $output .= '<div class="detailed-ratings">';
            $output .= '<h3>' . esc_html__('Detailed Ratings', 'advanced-product-review-system') . '</h3>';
            foreach ($ratings as $rating) {
                $score = isset($rating['score']) && is_numeric($rating['score']) ? floatval($rating['score']) : 0;
                $percentage = ($score / 10) * 100;
                
                $output .= '<div class="rating-row">';
                $output .= '<span class="rating-label">' . esc_html($rating['label']) . '</span>';
                $output .= '<div class="rating-bar">';
                $output .= '<div class="rating-fill" data-width="' . esc_attr($percentage) . '" style="width: ' . esc_attr($percentage) . '%"></div>';
                $output .= '</div>';
                $output .= '<span class="rating-score">' . esc_html($score) . '</span>';
                $output .= '</div>';
            }
            $output .= '</div>';
        }

        // Pros and Cons
        if (!empty($pros) || !empty($cons)) {
            $output .= '<div class="pros-cons-section">';
            
            if (!empty($pros)) {
                $output .= '<div class="pros-box">';
                $output .= '<h4>✓ ' . esc_html__('Pros', 'advanced-product-review-system') . '</h4>';
                $output .= '<ul>';
                foreach ($pros as $pro) {
                    $output .= '<li>' . esc_html($pro) . '</li>';
                }
                $output .= '</ul>';
                $output .= '</div>';
            }
            
            if (!empty($cons)) {
                $output .= '<div class="cons-box">';
                $output .= '<h4>✗ ' . esc_html__('Cons', 'advanced-product-review-system') . '</h4>';
                $output .= '<ul>';
                foreach ($cons as $con) {
                    $output .= '<li>' . esc_html($con) . '</li>';
                }
                $output .= '</ul>';
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }

        // Price Comparison
        if (!empty($product_prices)) {
            $output .= '<div class="collapsible-section price-section">';
            $output .= '<div class="collapsible-header" onclick="toggleCollapsible(this)">';
            $output .= esc_html(get_the_title()) . ' - ' . esc_html__('Price Comparison', 'advanced-product-review-system');
            $output .= '</div>';
            $output .= '<div class="collapsible-content">';
            $output .= '<div class="price-comparison-container">';

            foreach ($product_prices as $price) {
                if (!empty($price['store']) && !empty($price['price'])) {
                    $formatted_price = is_numeric($price['price']) ? number_format((float)$price['price']) : $price['price'];
                    $output .= '<div class="price-item">';
                    $output .= '<div class="price-store">' . esc_html($price['store']) . '</div>';
                    $output .= '<div class="price-amount">₹ ' . esc_html($formatted_price) . '</div>';
                    $output .= '<div class="stock">' . esc_html($price['stock']) . '</div>';
                    if (!empty($price['url'])) {
                        $output .= '<div class="buy-now"><a href="' . esc_url($price['url']) . '" target="_blank" class="button">' . esc_html__('Buy Now', 'advanced-product-review-system') . '</a></div>';
                    }
                    $output .= '</div>';
                }
            }

            $output .= '</div></div></div>';
        }

        // Specifications
        if (!empty($features)) {
            $output .= '<div class="collapsible-section specs-section">';
            $output .= '<div class="collapsible-header" onclick="toggleCollapsible(this)">';
            $output .= esc_html__('Technical Specifications', 'advanced-product-review-system');
            $output .= '</div>';
            $output .= '<div class="collapsible-content">';
            $output .= '<div class="specifications-container">';

            foreach ($features as $feature) {
                if (!empty($feature['title'])) {
                    $output .= '<div class="feature-item">';
                    $output .= '<strong>' . esc_html($feature['title']) . '</strong>';
                    $output .= '<span>' . esc_html($feature['detail']) . '</span>';
                    $output .= '</div>';
                }
            }

            $output .= '</div></div></div>';
        }

        return $content . $output;
    }
}

// Initialize
new APRS_Frontend_Display();
