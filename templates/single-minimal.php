<?php
/**
 * Template Name: Minimal Review Template
 * Template Post Type: post
 * 
 * Simple, clean single post template for product reviews
 */
 if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>



<div class="minimal-review-template">
    
    <?php while (have_posts()) : the_post(); ?>
    
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        
        <!-- Header -->
        <header class="minimal-post-header">
            <h1 class="minimal-post-title"><?php the_title(); ?></h1>
            
            <div class="minimal-post-meta">
                <span><?php esc_html_e('By', 'advanced-product-review-system'); ?> <?php echo esc_html(get_the_author()); ?></span>
                <span><?php echo esc_html(get_the_date('F j, Y')); ?></span>
            </div>
        </header>
        
        <!-- Featured Image -->
        <?php if (has_post_thumbnail()) : ?>
        <div class="minimal-featured-image">
            <?php the_post_thumbnail('full'); ?>
        </div>
        <?php endif; ?>
        
        <!-- Content -->
        <div class="minimal-post-content">
            <?php the_content(); ?>
        </div>
        
        <?php
        // Display comments
        if (comments_open() || get_comments_number()) :
            echo '<div style="margin-top: 60px;">';
            comments_template();
            echo '</div>';
        endif;
        ?>
        
    </article>
    
    <?php endwhile; ?>
    
    <!-- Floating Rating Badge -->
    <?php
    $aprs_overall_rating = get_post_meta(get_the_ID(), '_overall_rating', true);
    if (!empty($aprs_overall_rating)) :
    ?>
    <div class="minimal-rating-badge">
        <div class="score"><?php echo esc_html($aprs_overall_rating); ?></div>
        <div class="label"><?php esc_html_e('Score', 'advanced-product-review-system'); ?></div>
    </div>
    <?php endif; ?>
    
</div>

<?php get_footer(); ?>
