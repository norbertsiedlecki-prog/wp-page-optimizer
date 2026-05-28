<?php
/**
 * Site Repair Module
 */

if (!defined('ABSPATH')) {
    exit;
}

// Funkcja do naprawy bazy danych
function wpo_repair_database() {
    global $wpdb;
    
    // Usuń duplikaty postmeta
    $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_id NOT IN (SELECT MIN(meta_id) FROM {$wpdb->postmeta} GROUP BY post_id, meta_key)");
    
    // Usuń sierote postmeta
    $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})");
    
    // Usuń sierote termmeta
    $wpdb->query("DELETE FROM {$wpdb->termmeta} WHERE term_id NOT IN (SELECT term_id FROM {$wpdb->terms})");
    
    return true;
}

// Funkcja do czyszczenia cache
function wpo_clear_all_cache() {
    // WP Object Cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    // Transients
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_%'");
    
    return true;
}

// Funkcja do znalezienia problemów
function wpo_check_site_health() {
    $issues = [];
    
    // Sprawdź wersję PHP
    if (version_compare(phpversion(), '7.4', '<')) {
        $issues[] = [
            'type' => 'error',
            'message' => 'Wersja PHP jest zbyt stara. Zalecana: 7.4+',
        ];
    }
    
    // Sprawdź rozmiar bazy danych
    global $wpdb;
    $db_size = $wpdb->get_var("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "'");
    if ($db_size > 500) {
        $issues[] = [
            'type' => 'warning',
            'message' => "Baza danych jest duża ($db_size MB). Rozważ czyszczenie.",
        ];
    }
    
    // Sprawdź debugowanie
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $issues[] = [
            'type' => 'warning',
            'message' => 'WP_DEBUG jest włączony. Wyłącz na produkcji.',
        ];
    }
    
    return $issues;
}

// Auto-repair na aktywację
add_action('admin_init', 'wpo_auto_repair');
function wpo_auto_repair() {
    if (get_option('wpo_auto_repair_enabled')) {
        wpo_repair_database();
    }
}
?>