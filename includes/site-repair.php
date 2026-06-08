<?php
/**
 * Site Repair Module
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Funkcja do naprawy bazy danych
 * Wszystkie zapytania SQL są przygotowane z użyciem $wpdb->prepare()
 */
function wpo_repair_database() {
    global $wpdb;
    
    // Usuń duplikaty postmeta - używając przygotowanego zapytania
    $wpdb->query(
        $wpdb->prepare(
            "DELETE pm1 FROM {$wpdb->postmeta} pm1
            WHERE pm1.meta_id NOT IN (
                SELECT MIN(pm2.meta_id) FROM {$wpdb->postmeta} pm2
                WHERE pm2.post_id = pm1.post_id
                AND pm2.meta_key = pm1.meta_key
                GROUP BY pm2.post_id, pm2.meta_key
            )"
        )
    );
    
    // Usuń sierote postmeta - przygotowane zapytanie
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->postmeta}
            WHERE post_id NOT IN (
                SELECT ID FROM {$wpdb->posts}
            )"
        )
    );
    
    // Usuń sierote termmeta - przygotowane zapytanie
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->termmeta}
            WHERE term_id NOT IN (
                SELECT term_id FROM {$wpdb->terms}
            )"
        )
    );
    
    return true;
}

/**
 * Funkcja do czyszczenia cache
 */
function wpo_clear_all_cache() {
    // WP Object Cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    // Transients - używając przygotowanego zapytania
    global $wpdb;
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->options}
            WHERE option_name LIKE %s",
            '%_transient_%'
        )
    );
    
    return true;
}

/**
 * Funkcja do znalezienia problemów
 */
function wpo_check_site_health() {
    $issues = [];
    
    // Sprawdź wersję PHP
    if (version_compare(phpversion(), '7.4', '<')) {
        $issues[] = [
            'type' => 'error',
            'message' => 'Wersja PHP jest zbyt stara. Zalecana: 7.4+',
        ];
    }
    
    // Sprawdź rozmiar bazy danych - bezpiecznie z przygotowanym zapytaniem
    global $wpdb;
    $db_name = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT DATABASE()"
        )
    );
    
    if (!empty($db_name)) {
        $db_size = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2)
                FROM information_schema.tables
                WHERE table_schema = %s",
                $db_name[0]
            )
        );
        
        if ($db_size && $db_size > 500) {
            $issues[] = [
                'type' => 'warning',
                'message' => sprintf(
                    'Baza danych jest duża (%1$s MB). Rozważ czyszczenie.',
                    number_format($db_size, 2)
                ),
            ];
        }
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

/**
 * Usuń specyficzne wpisy postmeta
 * 
 * @param string $meta_key Klucz meta do usunięcia
 * @return int Liczba usuniętych wierszy
 */
function wpo_delete_postmeta_by_key($meta_key) {
    global $wpdb;
    
    return $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->postmeta}
            WHERE meta_key = %s",
            $meta_key
        )
    );
}

/**
 * Usuń sierote wpisy
 * 
 * @return int Liczba usuniętych wierszy
 */
function wpo_remove_orphaned_postmeta() {
    global $wpdb;
    
    return $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->postmeta}
            WHERE post_id NOT IN (
                SELECT ID FROM {$wpdb->posts}
            )"
        )
    );
}

/**
 * Pobierz statystyki bazy danych
 * 
 * @return array Tablica ze statystykami
 */
function wpo_get_database_stats() {
    global $wpdb;
    
    $db_name = $wpdb->get_col(
        $wpdb->prepare("SELECT DATABASE()")
    );
    
    if (empty($db_name)) {
        return [];
    }
    
    $stats = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb,
                ROUND(SUM(data_length) / 1024 / 1024, 2) as data_mb,
                ROUND(SUM(index_length) / 1024 / 1024, 2) as index_mb,
                COUNT(*) as table_count
            FROM information_schema.tables
            WHERE table_schema = %s",
            $db_name[0]
        ),
        ARRAY_A
    );
    
    return $stats ?: [];
}

/**
 * Auto-repair na aktywację
 */
add_action('admin_init', 'wpo_auto_repair');
function wpo_auto_repair() {
    if (get_option('wpo_auto_repair_enabled')) {
        wpo_repair_database();
    }
}

/**
 * Hook do czyszczenia orphaned meta co tydzień
 */
add_action('wpo_weekly_cleanup', 'wpo_remove_orphaned_postmeta');

// Zaplanuj cotygodniowe czyszczenie jeśli nie jest zaplanowane
if (!wp_next_scheduled('wpo_weekly_cleanup')) {
    wp_schedule_event(time(), 'weekly', 'wpo_weekly_cleanup');
}

?>
