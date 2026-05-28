<?php
/**
 * SEO Settings Module
 */

if (!defined('ABSPATH')) {
    exit;
}

// Generuj sitemap XML
add_action('init', 'wpo_generate_sitemap');
function wpo_generate_sitemap() {
    if (!get_option('wpo_generate_sitemap')) {
        return;
    }

    if (isset($_GET['sitemap.xml'])) {
        header('Content-Type: application/xml; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $args = [
            'post_type' => ['post', 'page'],
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];

        $posts = get_posts($args);
        foreach ($posts as $post) {
            echo '  <url>' . "\n";
            echo '    <loc>' . esc_url(get_permalink($post)) . '</loc>' . "\n";
            echo '    <lastmod>' . esc_html($post->post_modified) . '</lastmod>' . "\n";
            echo '    <priority>0.8</priority>' . "\n";
            echo '  </url>' . "\n";
        }

        echo '</urlset>';
        die();
    }
}

// Ustaw canonical URL
add_action('wp_head', 'wpo_add_canonical');
function wpo_add_canonical() {
    if (is_singular()) {
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '" />' . "\n";
    }
}

// Open Graph tags
add_action('wp_head', 'wpo_add_og_tags');
function wpo_add_og_tags() {
    if (is_singular()) {
        global $post;
        
        $title = get_the_title();
        $description = get_the_excerpt() ?: get_option('wpo_seo_description');
        $image = get_the_post_thumbnail_url() ?: get_option('siteurl') . '/wp-content/uploads/default.jpg';
        
        echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
        echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
    }
}

// Twitter Card tags
add_action('wp_head', 'wpo_add_twitter_tags');
function wpo_add_twitter_tags() {
    if (is_singular()) {
        $title = get_the_title();
        $description = get_the_excerpt() ?: get_option('wpo_seo_description');
        $image = get_the_post_thumbnail_url();
        
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
        if ($image) {
            echo '<meta name="twitter:image" content="' . esc_url($image) . '" />' . "\n";
        }
    }
}

// Robots meta tag
add_action('wp_head', 'wpo_add_robots_tag');
function wpo_add_robots_tag() {
    echo '<meta name="robots" content="index, follow" />' . "\n";
    echo '<meta name="googlebot" content="index, follow" />' . "\n";
}
?>