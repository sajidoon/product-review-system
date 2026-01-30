<?php
/**
 * Template Name: Product Review Template
 * Template Post Type: post
 * 
 * Custom single post template for product reviews
 * Place this file in your theme folder
 */

get_header(); ?>

<style>
/* Custom Single Post Template Styles */
.product-review-template {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.product-review-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    margin-top: 30px;
}

.main-content-area {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.sidebar-area {
    position: sticky;
    top: 20px;
    height: fit-content;
}

.product-title {
    font-size: 36px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    line-height: 1.2;
}

.product-meta {
    display: flex;
    gap: 20px;
    padding: 15px 0;
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 30px;
    font-size: 14px;
    color: #666;
}

.product-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.featured-image-wrapper {
    margin: 30px 0;
    border-radius: 10px;
    overflow: hidden;
}

.featured-image-wrapper img {
    width: 100%;
    height: auto;
    display: block;
}

.post-content {
    font-size: 16px;
    line-height: 1.8;
    color: #444;
}

.sidebar-widget {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.sidebar-widget h3 {
    margin: 0 0 20px 0;
    font-size: 20px;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
}

.quick-specs-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.quick-specs-list li {
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
}

.quick-specs-list li:last-child {
    border-bottom: none;
}

.quick-specs-list strong {
    color: #333;
}

.quick-specs-list span {
    color: #667eea;
    font-weight: 600;
}

@media (max-width: 992px) {
    .product-review-container {
        grid-template-columns: 1fr;
    }
    
    .sidebar-area {
        position: static;
    }
}
</style>

<div class="product-review-template">
    
    <?php while (have_posts()) : the_post(); ?>
    
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        
        <!-- Product Title -->
        <h1 class="product-title"><?php the_title(); ?></h1>
        
        <!-- Product Meta Info -->
        <div class="product-meta">
            <span>
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                </svg>
                <?php _e('By', 'advanced-product-review-system'); ?> <?php the_author(); ?>
            </span>
            <span>
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                </svg>
                <?php echo get_the_date(); ?>
            </span>
            <span>
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                    <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                </svg>
                <?php _e('Updated:', 'advanced-product-review-system'); ?> <?php echo get_the_modified_date(); ?>
            </span>
        </div>
        
        <div class="product-review-container">
            
            <!-- Main Content Area -->
            <div class="main-content-area">
                
                <?php if (has_post_thumbnail()) : ?>
                <div class="featured-image-wrapper">
                    <?php the_post_thumbnail('large'); ?>
                </div>
                <?php endif; ?>
                
                <div class="post-content">
                    <?php the_content(); ?>
                </div>
                
                <?php
                // Display comments if enabled
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
                
            </div>
            
            <!-- Sidebar Area -->
            <div class="sidebar-area">
                
                <?php
                // Get product data
                $overall_rating = get_post_meta(get_the_ID(), '_overall_rating', true);
                $features = get_post_meta(get_the_ID(), '_features', true);
                $product_prices = get_post_meta(get_the_ID(), '_product_prices', true);
                ?>
                
                <!-- Overall Rating Widget -->
                <?php if (!empty($overall_rating)) : ?>
                <div class="sidebar-widget">
                    <div style="text-align: center; padding: 20px;">
                        <div style="font-size: 60px; font-weight: bold; color: #667eea; line-height: 1;">
                            <?php echo esc_html($overall_rating); ?>
                        </div>
                        <div style="font-size: 14px; color: #666; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px;">
                            <?php _e('Overall Score', 'advanced-product-review-system'); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Quick Specs Widget -->
                <?php if (!empty($features) && is_array($features)) : ?>
                <div class="sidebar-widget">
                    <h3><?php _e('Quick Specs', 'advanced-product-review-system'); ?></h3>
                    <ul class="quick-specs-list">
                        <?php 
                        $count = 0;
                        foreach ($features as $feature) : 
                            if ($count >= 5) break; // Show only first 5
                            if (!empty($feature['title'])) :
                        ?>
                        <li>
                            <strong><?php echo esc_html($feature['title']); ?>:</strong>
                            <span><?php echo esc_html($feature['detail']); ?></span>
                        </li>
                        <?php 
                            $count++;
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Best Price Widget -->
                <?php if (!empty($product_prices) && is_array($product_prices)) : ?>
                <div class="sidebar-widget">
                    <h3><?php _e('Best Price', 'advanced-product-review-system'); ?></h3>
                    <?php
                    // Find the lowest price
                    $lowest_price = null;
                    foreach ($product_prices as $price_item) {
                        if (!empty($price_item['price']) && is_numeric($price_item['price'])) {
                            if ($lowest_price === null || floatval($price_item['price']) < floatval($lowest_price['price'])) {
                                $lowest_price = $price_item;
                            }
                        }
                    }
                    
                    if ($lowest_price) :
                        $formatted_price = number_format((float)$lowest_price['price']);
                    ?>
                    <div style="text-align: center; padding: 15px;">
                        <div style="font-size: 14px; color: #666; margin-bottom: 10px;">
                            <?php _e('Lowest Price at', 'advanced-product-review-system'); ?>
                        </div>
                        <div style="font-size: 20px; font-weight: bold; color: #333; margin-bottom: 5px;">
                            <?php echo esc_html($lowest_price['store']); ?>
                        </div>
                        <div style="font-size: 32px; font-weight: bold; color: #f5576c; margin: 15px 0;">
                            ₹<?php echo esc_html($formatted_price); ?>
                        </div>
                        <?php if (!empty($lowest_price['url'])) : ?>
                        <a href="<?php echo esc_url($lowest_price['url']); ?>" 
                           target="_blank" 
                           rel="nofollow"
                           style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 25px; font-weight: 600; margin-top: 10px;">
                            <?php _e('Buy Now', 'advanced-product-review-system'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Share Widget -->
                <div class="sidebar-widget">
                    <h3><?php _e('Share This Review', 'advanced-product-review-system'); ?></h3>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank"
                           style="flex: 1; padding: 10px; background: #3b5998; color: white; text-align: center; border-radius: 5px; text-decoration: none; min-width: 80px;">
                            <?php _e('Facebook', 'advanced-product-review-system'); ?>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                           target="_blank"
                           style="flex: 1; padding: 10px; background: #1da1f2; color: white; text-align: center; border-radius: 5px; text-decoration: none; min-width: 80px;">
                            <?php _e('Twitter', 'advanced-product-review-system'); ?>
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                           target="_blank"
                           style="flex: 1; padding: 10px; background: #25d366; color: white; text-align: center; border-radius: 5px; text-decoration: none; min-width: 80px;">
                            <?php _e('WhatsApp', 'advanced-product-review-system'); ?>
                        </a>
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </article>
    
    <?php endwhile; ?>
    
</div>

<?php get_footer(); ?>
