<?php
/**
 * Performance Module
 */

if (!defined('ABSPATH')) {
    exit;
}

// Monitor wydajności
add_action('admin_init', 'wpo_performance_monitor');
function wpo_performance_monitor() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $metrics = get_option('wpo_performance_metrics', []);
    $metrics['last_check'] = current_time('mysql');
    $metrics['server_load'] = function_exists('sys_getloadavg') ? sys_getloadavg() : 'N/A';
    
    update_option('wpo_performance_metrics', $metrics);
}

// Wyświetl metryki wydajności
function wpo_get_performance_metrics() {
    return get_option('wpo_performance_metrics', []);
}

// Optymalizacja obrazów
add_filter('wp_get_attachment_image_attributes', 'wpo_optimize_images');
function wpo_optimize_images($attr) {
    if (get_option('wpo_lazy_loading')) {
        $attr['loading'] = 'lazy';
    }
    
    if (!isset($attr['decoding'])) {
        $attr['decoding'] = 'async';
    }
    
    return $attr;
}

// Preload ważnych zasobów
add_action('wp_head', 'wpo_preload_critical_resources', 1);
function wpo_preload_critical_resources() {
    if (is_front_page()) {
        echo '<link rel="preload" as="font" href="' . WPO_PLUGIN_URL . 'assets/fonts/main.woff2" type="font/woff2" crossorigin />' . "\n";
    }
}
?>