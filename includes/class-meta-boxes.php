<?php
/**
 * Meta Boxes Class
 * Handles all admin meta boxes for product information
 */

// Enqueue CSS and JS
function enqueue_advanced_product_styles() {
    if (is_single()) {
        wp_enqueue_style(
            'advanced-product-styles',
            plugin_dir_url(__FILE__) . 'advanced-product-styles.css',
            array(),
            '2.0'
        );
        wp_enqueue_script(
            'advanced-product-scripts',
            plugin_dir_url(__FILE__) . 'advanced-product-scripts.js',
            array('jquery'),
            '2.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_advanced_product_styles');

// Add Meta Box
function add_advanced_product_meta_box() {
    add_meta_box(
        'advanced_product_meta_box',
        __('Advanced Product Information', 'product-review-system'),
        'render_advanced_product_meta_box',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_advanced_product_meta_box');

// Render Meta Box
function render_advanced_product_meta_box($post) {
    wp_nonce_field('save_advanced_product', 'advanced_product_nonce');
    
    // Get existing data
    $features = get_post_meta($post->ID, '_features', true) ?: [];
    $product_prices = get_post_meta($post->ID, '_product_prices', true) ?: [];
    $ratings = get_post_meta($post->ID, '_ratings', true) ?: [];
    $pros = get_post_meta($post->ID, '_pros', true) ?: [];
    $cons = get_post_meta($post->ID, '_cons', true) ?: [];
    $overall_rating = get_post_meta($post->ID, '_overall_rating', true) ?: '0';
    $editor_choice = get_post_meta($post->ID, '_editor_choice', true) ?: '';
    
    ?>
    <div class="advanced-product-meta-box">
        
        <!-- Overall Rating -->
        <div class="meta-section">
            <h3><?php _e('Overall Rating', 'product-review-system'); ?></h3>
            <label><?php _e('Rating Score (0-10):', 'product-review-system'); ?></label>
            <input type="number" step="0.1" min="0" max="10" name="overall_rating" value="<?php echo esc_attr($overall_rating); ?>" class="widefat" />
            
            <label style="margin-top: 10px;">
                <input type="checkbox" name="editor_choice" value="1" <?php checked($editor_choice, '1'); ?> />
                <?php _e("Editor's Choice", 'product-review-system'); ?>
            </label>
        </div>

        <!-- Ratings Section -->
        <div class="meta-section">
            <h3><?php _e('Detailed Ratings', 'product-review-system'); ?></h3>
            <div id="ratings-container">
                <?php
                if (!empty($ratings)) {
                    foreach ($ratings as $index => $rating) {
                        ?>
                        <div class="rating-item" id="rating-item-<?php echo $index; ?>">
                            <input type="text" name="ratings[<?php echo $index; ?>][label]" value="<?php echo esc_attr($rating['label']); ?>" placeholder="<?php esc_attr_e('Rating Label (e.g., Design)', 'product-review-system'); ?>" class="widefat" />
                            <input type="number" step="0.1" min="0" max="10" name="ratings[<?php echo $index; ?>][score]" value="<?php echo esc_attr($rating['score']); ?>" placeholder="<?php esc_attr_e('Score (0-10)', 'product-review-system'); ?>" class="widefat" />
                            <button type="button" class="button remove-rating" data-index="<?php echo $index; ?>"><?php _e('Remove', 'product-review-system'); ?></button>
                            <hr />
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="rating-item" id="rating-item-0">
                        <input type="text" name="ratings[0][label]" placeholder="<?php esc_attr_e('Rating Label', 'product-review-system'); ?>" class="widefat" />
                        <input type="number" step="0.1" min="0" max="10" name="ratings[0][score]" placeholder="<?php esc_attr_e('Score (0-10)', 'product-review-system'); ?>" class="widefat" />
                        <button type="button" class="button remove-rating" data-index="0"><?php _e('Remove', 'product-review-system'); ?></button>
                        <hr />
                    </div>
                    <?php
                }
                ?>
            </div>
            <button type="button" id="add-rating-button" class="button"><?php _e('Add Rating', 'product-review-system'); ?></button>
        </div>

        <!-- Pros Section -->
        <div class="meta-section">
            <h3><?php _e('Pros', 'product-review-system'); ?></h3>
            <div id="pros-container">
                <?php
                if (!empty($pros)) {
                    foreach ($pros as $index => $pro) {
                        ?>
                        <div class="pro-item" id="pro-item-<?php echo $index; ?>">
                            <input type="text" name="pros[<?php echo $index; ?>]" value="<?php echo esc_attr($pro); ?>" class="widefat" />
                            <button type="button" class="button remove-pro" data-index="<?php echo $index; ?>"><?php _e('Remove', 'product-review-system'); ?></button>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <button type="button" id="add-pro-button" class="button"><?php _e('Add Pro', 'product-review-system'); ?></button>
        </div>

        <!-- Cons Section -->
        <div class="meta-section">
            <h3><?php _e('Cons', 'product-review-system'); ?></h3>
            <div id="cons-container">
                <?php
                if (!empty($cons)) {
                    foreach ($cons as $index => $con) {
                        ?>
                        <div class="con-item" id="con-item-<?php echo $index; ?>">
                            <input type="text" name="cons[<?php echo $index; ?>]" value="<?php echo esc_attr($con); ?>" class="widefat" />
                            <button type="button" class="button remove-con" data-index="<?php echo $index; ?>"><?php _e('Remove', 'product-review-system'); ?></button>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <button type="button" id="add-con-button" class="button"><?php _e('Add Con', 'product-review-system'); ?></button>
        </div>

        <!-- Features Section -->
        <div class="meta-section">
            <h3><?php _e('Product Specifications', 'product-review-system'); ?></h3>
            <div id="features-container">
                <?php
                if (!empty($features)) {
                    foreach ($features as $index => $feature) {
                        ?>
                        <div class="feature-item" id="feature-item-<?php echo $index; ?>">
                            <input type="text" name="features[<?php echo $index; ?>][title]" value="<?php echo esc_attr($feature['title']); ?>" placeholder="<?php esc_attr_e('Feature Title', 'product-review-system'); ?>" class="widefat" />
                            <textarea name="features[<?php echo $index; ?>][detail]" placeholder="<?php esc_attr_e('Feature Detail', 'product-review-system'); ?>" class="widefat"><?php echo esc_textarea($feature['detail']); ?></textarea>
                            <button type="button" class="button remove-feature" data-index="<?php echo $index; ?>"><?php _e('Remove', 'product-review-system'); ?></button>
                            <hr />
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <button type="button" id="add-feature-button" class="button"><?php _e('Add Feature', 'product-review-system'); ?></button>
        </div>

        <!-- Price Comparison -->
        <div class="meta-section">
            <h3><?php _e('Price Comparison', 'product-review-system'); ?></h3>
            <div id="price-comparison-container">
                <?php
                if (!empty($product_prices)) {
                    foreach ($product_prices as $index => $price) {
                        ?>
                        <div class="price-item" id="price-item-<?php echo $index; ?>">
                            <input type="text" name="product_prices[<?php echo $index; ?>][store]" value="<?php echo esc_attr($price['store']); ?>" placeholder="<?php esc_attr_e('Store Name', 'product-review-system'); ?>" class="widefat" />
                            <input type="text" name="product_prices[<?php echo $index; ?>][price]" value="<?php echo esc_attr($price['price']); ?>" placeholder="<?php esc_attr_e('Price', 'product-review-system'); ?>" class="widefat" />
                            <input type="text" name="product_prices[<?php echo $index; ?>][url]" value="<?php echo esc_attr($price['url']); ?>" placeholder="<?php esc_attr_e('Buy URL', 'product-review-system'); ?>" class="widefat" />
                            <input type="text" name="product_prices[<?php echo $index; ?>][stock]" value="<?php echo esc_attr($price['stock']); ?>" placeholder="<?php esc_attr_e('Stock Status', 'product-review-system'); ?>" class="widefat" />
                            <button type="button" class="button remove-price-button" data-index="<?php echo $index; ?>"><?php _e('Remove', 'product-review-system'); ?></button>
                            <hr />
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <button type="button" id="add-price-button" class="button"><?php _e('Add Store Price', 'product-review-system'); ?></button>
        </div>

    </div>

    <style>
        .meta-section { margin-bottom: 30px; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; }
        .meta-section h3 { margin-top: 0; border-bottom: 2px solid #0073aa; padding-bottom: 10px; }
        .rating-item, .pro-item, .con-item, .feature-item, .price-item { margin-bottom: 15px; padding: 10px; background: white; border: 1px solid #ddd; }
        .widefat { margin-bottom: 5px; }
    </style>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Add Rating
            $('#add-rating-button').on('click', function() {
                var container = $('#ratings-container');
                var index = container.find('.rating-item').length;
                var html = `
                    <div class="rating-item" id="rating-item-${index}">
                        <input type="text" name="ratings[${index}][label]" placeholder="Rating Label" class="widefat" />
                        <input type="number" step="0.1" min="0" max="10" name="ratings[${index}][score]" placeholder="Score" class="widefat" />
                        <button type="button" class="button remove-rating" data-index="${index}">Remove</button>
                        <hr />
                    </div>
                `;
                container.append(html);
            });

            $(document).on('click', '.remove-rating', function() {
                $(this).closest('.rating-item').remove();
            });

            // Add Pro
            $('#add-pro-button').on('click', function() {
                var container = $('#pros-container');
                var index = container.find('.pro-item').length;
                var html = `
                    <div class="pro-item" id="pro-item-${index}">
                        <input type="text" name="pros[${index}]" class="widefat" />
                        <button type="button" class="button remove-pro" data-index="${index}">Remove</button>
                    </div>
                `;
                container.append(html);
            });

            $(document).on('click', '.remove-pro', function() {
                $(this).closest('.pro-item').remove();
            });

            // Add Con
            $('#add-con-button').on('click', function() {
                var container = $('#cons-container');
                var index = container.find('.con-item').length;
                var html = `
                    <div class="con-item" id="con-item-${index}">
                        <input type="text" name="cons[${index}]" class="widefat" />
                        <button type="button" class="button remove-con" data-index="${index}">Remove</button>
                    </div>
                `;
                container.append(html);
            });

            $(document).on('click', '.remove-con', function() {
                $(this).closest('.con-item').remove();
            });

            // Add Feature
            $('#add-feature-button').on('click', function() {
                var container = $('#features-container');
                var index = container.find('.feature-item').length;
                var html = `
                    <div class="feature-item" id="feature-item-${index}">
                        <input type="text" name="features[${index}][title]" placeholder="Feature Title" class="widefat" />
                        <textarea name="features[${index}][detail]" placeholder="Feature Detail" class="widefat"></textarea>
                        <button type="button" class="button remove-feature" data-index="${index}">Remove</button>
                        <hr />
                    </div>
                `;
                container.append(html);
            });

            $(document).on('click', '.remove-feature', function() {
                $(this).closest('.feature-item').remove();
            });

            // Add Price
            $('#add-price-button').on('click', function() {
                var container = $('#price-comparison-container');
                var index = container.find('.price-item').length;
                var html = `
                    <div class="price-item" id="price-item-${index}">
                        <input type="text" name="product_prices[${index}][store]" placeholder="Store Name" class="widefat" />
                        <input type="text" name="product_prices[${index}][price]" placeholder="Price" class="widefat" />
                        <input type="text" name="product_prices[${index}][url]" placeholder="Buy URL" class="widefat" />
                        <input type="text" name="product_prices[${index}][stock]" placeholder="Stock Status" class="widefat" />
                        <button type="button" class="button remove-price-button" data-index="${index}">Remove</button>
                        <hr />
                    </div>
                `;
                container.append(html);
            });

            $(document).on('click', '.remove-price-button', function() {
                $(this).closest('.price-item').remove();
            });
        });
    </script>
    <?php
}

// Save Meta Box Data
function save_advanced_product_meta($post_id) {
    if (!isset($_POST['advanced_product_nonce']) || !wp_verify_nonce($_POST['advanced_product_nonce'], 'save_advanced_product')) {
        return $post_id;
    }

    // Save Overall Rating
    if (isset($_POST['overall_rating'])) {
        update_post_meta($post_id, '_overall_rating', sanitize_text_field($_POST['overall_rating']));
    }

    // Save Editor's Choice
    update_post_meta($post_id, '_editor_choice', isset($_POST['editor_choice']) ? '1' : '');

    // Save Ratings
    if (isset($_POST['ratings']) && is_array($_POST['ratings'])) {
        $ratings = [];
        foreach ($_POST['ratings'] as $rating) {
            if (!empty($rating['label'])) {
                $ratings[] = [
                    'label' => sanitize_text_field($rating['label']),
                    'score' => sanitize_text_field($rating['score']),
                ];
            }
        }
        update_post_meta($post_id, '_ratings', $ratings);
    }

    // Save Pros
    if (isset($_POST['pros']) && is_array($_POST['pros'])) {
        $pros = array_filter(array_map('sanitize_text_field', $_POST['pros']));
        update_post_meta($post_id, '_pros', $pros);
    }

    // Save Cons
    if (isset($_POST['cons']) && is_array($_POST['cons'])) {
        $cons = array_filter(array_map('sanitize_text_field', $_POST['cons']));
        update_post_meta($post_id, '_cons', $cons);
    }

    // Save Features
    if (isset($_POST['features']) && is_array($_POST['features'])) {
        $features = [];
        foreach ($_POST['features'] as $feature) {
            if (!empty($feature['title'])) {
                $features[] = [
                    'title' => sanitize_text_field($feature['title']),
                    'detail' => sanitize_textarea_field($feature['detail']),
                ];
            }
        }
        update_post_meta($post_id, '_features', $features);
    }

    // Save Prices
    if (isset($_POST['product_prices']) && is_array($_POST['product_prices'])) {
        $product_prices = [];
        foreach ($_POST['product_prices'] as $price) {
            if (!empty($price['store'])) {
                $product_prices[] = [
                    'store' => sanitize_text_field($price['store']),
                    'price' => sanitize_text_field($price['price']),
                    'url' => esc_url_raw($price['url']),
                    'stock' => sanitize_text_field($price['stock']),
                ];
            }
        }
        update_post_meta($post_id, '_product_prices', $product_prices);
    }
}
add_action('save_post', 'save_advanced_product_meta');


