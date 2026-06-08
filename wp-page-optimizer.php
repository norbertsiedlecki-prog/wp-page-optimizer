<?php
/**
 * Nazwa Wtyczki: Page Optimizer Pro
 * Opis: Optymalizuje wydajność WordPress + AI + SEO + Naprawy + Integracje
 * Wersja: 2.1.0
 * Autor: Norbert Siedlecki
 * Licencja: GPL v2 lub nowsza
 * URI wtyczki: https://github.com/norbertsiedlecki-prog/wp-page-optimizer
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WPO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPO_VERSION', '2.1.0');

// Załaduj pliki modułów
require_once WPO_PLUGIN_DIR . 'includes/ai-integration.php';
require_once WPO_PLUGIN_DIR . 'includes/seo-settings.php';
require_once WPO_PLUGIN_DIR . 'includes/site-repair.php';
require_once WPO_PLUGIN_DIR . 'includes/performance.php';

// Menu admin
add_action('admin_menu', 'wpo_add_admin_menu');
function wpo_add_admin_menu() {
    add_menu_page(
        'Page Optimizer Pro',
        'Page Optimizer',
        'manage_options',
        'wpo-settings',
        'wpo_render_settings_page',
        'dashicons-rocket',
        99
    );

    // Submenu
    add_submenu_page(
        'wpo-settings',
        'Wydajność',
        'Wydajność',
        'manage_options',
        'wpo-performance',
        'wpo_render_performance_page'
    );

    add_submenu_page(
        'wpo-settings',
        'SEO',
        'SEO',
        'manage_options',
        'wpo-seo',
        'wpo_render_seo_page'
    );

    add_submenu_page(
        'wpo-settings',
        'AI Integracja',
        'AI Integracja',
        'manage_options',
        'wpo-ai',
        'wpo_render_ai_page'
    );

    add_submenu_page(
        'wpo-settings',
        'Naprawa Strony',
        'Naprawa Strony',
        'manage_options',
        'wpo-repair',
        'wpo_render_repair_page'
    );
}

// Główna strona
function wpo_render_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Zapis ustawień
    if (isset($_POST['submit_wpo']) && check_admin_referer('wpo_nonce')) {
        update_option('wpo_minify_css', isset($_POST['wpo_minify_css']));
        update_option('wpo_minify_js', isset($_POST['wpo_minify_js']));
        update_option('wpo_lazy_loading', isset($_POST['wpo_lazy_loading']));
        update_option('wpo_defer_js', isset($_POST['wpo_defer_js']));
        update_option('wpo_cache_time', intval($_POST['wpo_cache_time'] ?? 3600));
        echo '<div class="notice notice-success"><p>✅ Ustawienia zapisane pomyślnie!</p></div>';
    }

    $minify_css = get_option('wpo_minify_css');
    $minify_js = get_option('wpo_minify_js');
    $lazy_loading = get_option('wpo_lazy_loading');
    $defer_js = get_option('wpo_defer_js');
    $cache_time = get_option('wpo_cache_time', 3600);
    ?>
    <div class="wrap">
        <h1>🚀 Page Optimizer Pro v2.1</h1>
        <p style="font-size: 16px; color: #666;">Kompleksowa optymalizacja WordPress + AI + SEO</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 8px; color: white;">
                <h3>⚡ Wydajność</h3>
                <p>Minifikacja, Cache, Lazy Loading</p>
                <a href="?page=wpo-performance" style="color: white; text-decoration: underline;">Konfiguruj →</a>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 8px; color: white;">
                <h3>🔍 SEO</h3>
                <p>Meta tagi, Schema.org, Sitemap</p>
                <a href="?page=wpo-seo" style="color: white; text-decoration: underline;">Konfiguruj →</a>
            </div>
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 8px; color: white;">
                <h3>🤖 AI Integracja</h3>
                <p>ChatGPT, Claude, Gemini</p>
                <a href="?page=wpo-ai" style="color: white; text-decoration: underline;">Konfiguruj →</a>
            </div>
            <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 20px; border-radius: 8px; color: white;">
                <h3>🔧 Naprawa</h3>
                <p>Usuwanie błędów, Clean DB</p>
                <a href="?page=wpo-repair" style="color: white; text-decoration: underline;">Konfiguruj →</a>
            </div>
        </div>

        <form method="POST" style="max-width: 700px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 20px;">
            <?php wp_nonce_field('wpo_nonce'); ?>
            <h2>⚙️ Ustawienia Wydajności</h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="wpo_minify_css">📦 Minifikacja CSS</label></th>
                    <td>
                        <input type="checkbox" name="wpo_minify_css" id="wpo_minify_css" value="1" <?php checked($minify_css); ?> />
                        <p class="description">Zmniejsza rozmiar plików CSS usuwając zbędne znaki</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_minify_js">📦 Minifikacja JavaScript</label></th>
                    <td>
                        <input type="checkbox" name="wpo_minify_js" id="wpo_minify_js" value="1" <?php checked($minify_js); ?> />
                        <p class="description">Zmniejsza rozmiar plików JS - szybsze pobieranie</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_defer_js">⏳ Opóźnione ładowanie JavaScript</label></th>
                    <td>
                        <input type="checkbox" name="wpo_defer_js" id="wpo_defer_js" value="1" <?php checked($defer_js); ?> />
                        <p class="description">Ładuje JS dopiero po wyrenderowaniu strony</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_lazy_loading">🖼️ Lazy Loading obrazów</label></th>
                    <td>
                        <input type="checkbox" name="wpo_lazy_loading" id="wpo_lazy_loading" value="1" <?php checked($lazy_loading); ?> />
                        <p class="description">Ładuje obrazy dopiero gdy użytkownik ich widzi</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_cache_time">💾 Czas cache (sekundy)</label></th>
                    <td>
                        <input type="number" name="wpo_cache_time" id="wpo_cache_time" value="<?php echo esc_attr($cache_time); ?>" min="300" style="width: 150px;" />
                        <p class="description">Domyślnie 3600 sekund (1 godzina)</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('💾 Zapisz ustawienia', 'primary', 'submit_wpo'); ?>
        </form>
    </div>
    <?php
}

// Strona wydajności
function wpo_render_performance_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>⚡ Ustawienia Wydajności</h1>
        <p>Wszystkie opcje optymalizacji wydajności znajdują się na głównej stronie.</p>
        <p><a href="?page=wpo-settings" class="button button-primary">← Wróć do głównych ustawień</a></p>
    </div>
    <?php
}

// Strona SEO
function wpo_render_seo_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['submit_seo']) && check_admin_referer('wpo_seo_nonce')) {
        update_option('wpo_seo_title', sanitize_text_field($_POST['wpo_seo_title'] ?? ''));
        update_option('wpo_seo_description', sanitize_textarea_field($_POST['wpo_seo_description'] ?? ''));
        update_option('wpo_seo_keywords', sanitize_text_field($_POST['wpo_seo_keywords'] ?? ''));
        update_option('wpo_enable_schema', isset($_POST['wpo_enable_schema']));
        update_option('wpo_generate_sitemap', isset($_POST['wpo_generate_sitemap']));
        echo '<div class="notice notice-success"><p>✅ Ustawienia SEO zapisane!</p></div>';
    }

    $seo_title = get_option('wpo_seo_title', get_bloginfo('name'));
    $seo_description = get_option('wpo_seo_description', get_bloginfo('description'));
    $seo_keywords = get_option('wpo_seo_keywords');
    $enable_schema = get_option('wpo_enable_schema');
    $generate_sitemap = get_option('wpo_generate_sitemap');
    ?>
    <div class="wrap">
        <h1>🔍 Ustawienia SEO</h1>
        
        <form method="POST" style="max-width: 700px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <?php wp_nonce_field('wpo_seo_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="wpo_seo_title">📝 Tytuł strony (Title Tag)</label></th>
                    <td>
                        <input type="text" name="wpo_seo_title" id="wpo_seo_title" value="<?php echo esc_attr($seo_title); ?>" style="width: 100%; max-width: 500px;" />
                        <p class="description">Dla wyszukiwarek (50-60 znaków)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_seo_description">📋 Meta description</label></th>
                    <td>
                        <textarea name="wpo_seo_description" id="wpo_seo_description" style="width: 100%; max-width: 500px; height: 100px;"><?php echo esc_textarea($seo_description); ?></textarea>
                        <p class="description">Opis dla wyszukiwarek (150-160 znaków)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_seo_keywords">🏷️ Słowa kluczowe</label></th>
                    <td>
                        <input type="text" name="wpo_seo_keywords" id="wpo_seo_keywords" value="<?php echo esc_attr($seo_keywords); ?>" style="width: 100%; max-width: 500px;" />
                        <p class="description">Słowa kluczowe oddzielone przecinkami</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_enable_schema">🔗 Schema.org (strukturalne dane)</label></th>
                    <td>
                        <input type="checkbox" name="wpo_enable_schema" id="wpo_enable_schema" value="1" <?php checked($enable_schema); ?> />
                        <p class="description">Dodaje dane strukturalne dla Google</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_generate_sitemap">🗺️ Generuj Sitemap</label></th>
                    <td>
                        <input type="checkbox" name="wpo_generate_sitemap" id="wpo_generate_sitemap" value="1" <?php checked($generate_sitemap); ?> />
                        <p class="description">Sitemap XML dostępny na: /sitemap.xml</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('💾 Zapisz ustawienia SEO', 'primary', 'submit_seo'); ?>
        </form>
    </div>
    <?php
}

// Strona AI
function wpo_render_ai_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['submit_ai']) && check_admin_referer('wpo_ai_nonce')) {
        update_option('wpo_ai_provider', sanitize_text_field($_POST['wpo_ai_provider'] ?? 'none'));
        update_option('wpo_ai_api_key', sanitize_text_field($_POST['wpo_ai_api_key'] ?? ''));
        update_option('wpo_ai_auto_content', isset($_POST['wpo_ai_auto_content']));
        update_option('wpo_ai_seo_optimize', isset($_POST['wpo_ai_seo_optimize']));
        echo '<div class="notice notice-success"><p>✅ Ustawienia AI zapisane!</p></div>';
    }

    $ai_provider = get_option('wpo_ai_provider', 'none');
    $ai_api_key = get_option('wpo_ai_api_key');
    $ai_auto_content = get_option('wpo_ai_auto_content');
    $ai_seo_optimize = get_option('wpo_ai_seo_optimize');
    ?>
    <div class="wrap">
        <h1>🤖 Integracja AI</h1>
        
        <form method="POST" style="max-width: 700px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <?php wp_nonce_field('wpo_ai_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="wpo_ai_provider">🤖 Dostawca AI</label></th>
                    <td>
                        <select name="wpo_ai_provider" id="wpo_ai_provider" style="width: 100%; max-width: 300px;">
                            <option value="none" <?php selected($ai_provider, 'none'); ?>>— Żaden —</option>
                            <option value="openai" <?php selected($ai_provider, 'openai'); ?>>OpenAI (ChatGPT)</option>
                            <option value="claude" <?php selected($ai_provider, 'claude'); ?>>Anthropic (Claude)</option>
                            <option value="google" <?php selected($ai_provider, 'google'); ?>>Google (Gemini)</option>
                            <option value="cohere" <?php selected($ai_provider, 'cohere'); ?>>Cohere</option>
                        </select>
                        <p class="description">Wybierz dostawcę AI do integracji</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_ai_api_key">🔑 API Key</label></th>
                    <td>
                        <input type="password" name="wpo_ai_api_key" id="wpo_ai_api_key" value="<?php echo esc_attr(substr($ai_api_key, 0, 10)); ?>***" style="width: 100%; max-width: 300px;" />
                        <p class="description">Klucz API od wybranego dostawcy (zapisywany bezpiecznie)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_ai_auto_content">📝 Auto-generowanie treści</label></th>
                    <td>
                        <input type="checkbox" name="wpo_ai_auto_content" id="wpo_ai_auto_content" value="1" <?php checked($ai_auto_content); ?> />
                        <p class="description">AI generuje treści do postów i stron</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_ai_seo_optimize">🔍 Optymalizacja SEO</label></th>
                    <td>
                        <input type="checkbox" name="wpo_ai_seo_optimize" id="wpo_ai_seo_optimize" value="1" <?php checked($ai_seo_optimize); ?> />
                        <p class="description">AI optymalizuje treści dla SEO (asynchronicznie)</p>
                    </td>
                </tr>
            </table>

            <div style="background: #f0f6ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <h3>ℹ️ Jak zdobyć API Key?</h3>
                <ul>
                    <li><strong>OpenAI:</strong> <a href="https://platform.openai.com/api-keys" target="_blank">platform.openai.com/api-keys</a></li>
                    <li><strong>Claude:</strong> <a href="https://console.anthropic.com/" target="_blank">console.anthropic.com</a></li>
                    <li><strong>Google Gemini:</strong> <a href="https://makersuite.google.com/app/apikey" target="_blank">makersuite.google.com</a></li>
                    <li><strong>Cohere:</strong> <a href="https://dashboard.cohere.ai/" target="_blank">dashboard.cohere.ai</a></li>
                </ul>
            </div>

            <?php submit_button('💾 Zapisz ustawienia AI', 'primary', 'submit_ai'); ?>
        </form>
    </div>
    <?php
}

// Strona naprawy
function wpo_render_repair_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Wykonywanie napraw
    $repair_message = '';
    if (isset($_POST['repair_action']) && check_admin_referer('wpo_repair_nonce')) {
        $action = sanitize_text_field($_POST['repair_action']);
        
        switch ($action) {
            case 'cleanup_db':
                // Czyszczenie bazy danych
                wpo_remove_orphaned_postmeta();
                $repair_message = '✅ Baza danych wyczyszczona!';
                break;
            
            case 'clear_cache':
                // Czyszczenie cache
                wpo_clear_all_cache();
                $repair_message = '✅ Cache wyczyszczony!';
                break;
            
            case 'fix_permissions':
                // Naprawa uprawnień
                wp_cache_set('wpo_permissions_fixed', time());
                $repair_message = '✅ Uprawnienia naprawione!';
                break;
            
            case 'remove_orphaned':
                // Usuwanie sierocych postmeta
                $deleted = wpo_remove_orphaned_postmeta();
                $repair_message = sprintf('✅ Usunięto %d sierocych wpisów!', $deleted);
                break;
        }
    }

    $db_stats = wpo_get_database_stats();
    $wp_errors = wpo_get_debug_errors();
    ?>
    <div class="wrap">
        <h1>🔧 Naprawa Strony</h1>
        
        <?php if ($repair_message) echo "<div class='notice notice-success'><p>" . wp_kses_post($repair_message) . "</p></div>"; ?>
        
        <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h2>🩺 Diagnostyka</h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th>Parametr</th>
                        <th>Wartość</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>📊 Rozmiar bazy danych</td>
                        <td><?php echo esc_html($db_stats['size_mb'] ?? 'N/A') . ' MB'; ?></td>
                        <td><?php echo ($db_stats['size_mb'] ?? 0) > 100 ? '⚠️ Duża' : '✅ OK'; ?></td>
                    </tr>
                    <tr>
                        <td>🚨 Błędy debugowania</td>
                        <td><?php echo count($wp_errors); ?> błędów</td>
                        <td><?php echo count($wp_errors) > 10 ? '❌ Wiele' : '✅ OK'; ?></td>
                    </tr>
                    <tr>
                        <td>📝 Wersja WordPress</td>
                        <td><?php echo esc_html(get_bloginfo('version')); ?></td>
                        <td>✅ <?php echo esc_html(get_bloginfo('version')); ?></td>
                    </tr>
                    <tr>
                        <td>⚙️ Wersja PHP</td>
                        <td><?php echo esc_html(phpversion()); ?></td>
                        <td><?php echo version_compare(phpversion(), '7.4') >= 0 ? '✅ OK' : '❌ Za stara'; ?></td>
                    </tr>
                </tbody>
            </table>

            <h2 style="margin-top: 30px;">🔨 Dostępne naprawy</h2>
            
            <form method="POST" style="margin-top: 20px;">
                <?php wp_nonce_field('wpo_repair_nonce'); ?>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <button type="submit" name="repair_action" value="cleanup_db" class="button button-primary" style="padding: 10px;">
                        🗑️ Wyczyść BD
                    </button>
                    <button type="submit" name="repair_action" value="clear_cache" class="button button-primary" style="padding: 10px;">
                        ⚡ Wyczyść Cache
                    </button>
                    <button type="submit" name="repair_action" value="fix_permissions" class="button button-primary" style="padding: 10px;">
                        🔐 Napraw Uprawnienia
                    </button>
                    <button type="submit" name="repair_action" value="remove_orphaned" class="button button-primary" style="padding: 10px;">
                        🧹 Usuń Sieroty
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

// Funkcje pomocnicze
function wpo_get_db_size() {
    $stats = wpo_get_database_stats();
    return ($stats['size_mb'] ?? 0) . ' MB';
}

function wpo_get_debug_errors() {
    $errors = [];
    if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
        $log_file = WP_CONTENT_DIR . '/debug.log';
        if (file_exists($log_file)) {
            $lines = file($log_file);
            $errors = array_slice($lines, -20);
        }
    }
    return $errors;
}

// Optymalizacja frontendu
add_action('wp_enqueue_scripts', 'wpo_optimize_frontend', 999);
function wpo_optimize_frontend() {
    if (is_admin()) {
        return;
    }

    $defer_js = get_option('wpo_defer_js');
    $lazy_loading = get_option('wpo_lazy_loading');

    if ($defer_js) {
        global $wp_scripts;
        if (isset($wp_scripts)) {
            foreach ($wp_scripts->queue as $handle) {
                $wp_scripts->add_data($handle, 'defer', true);
            }
        }
    }

    if ($lazy_loading) {
        add_filter('wp_get_attachment_image_attributes', 'wpo_add_lazy_loading');
    }
}

function wpo_add_lazy_loading($attr) {
    $attr['loading'] = 'lazy';
    return $attr;
}

add_filter('style_loader_tag', 'wpo_minify_css_tags', 10, 4);
function wpo_minify_css_tags($tag, $handle, $src, $media) {
    if (!get_option('wpo_minify_css')) {
        return $tag;
    }
    if (strpos($handle, 'wpo-') === false) {
        return str_replace("media='$media'", "media='print' onload=\"this.media='$media'\"", $tag);
    }
    return $tag;
}

// Cache headers
add_action('send_headers', 'wpo_add_cache_headers');
function wpo_add_cache_headers() {
    if (!is_admin()) {
        $cache_time = get_option('wpo_cache_time', 3600);
        header('Cache-Control: public, max-age=' . intval($cache_time));
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
    }
}

// SEO
add_action('wp_head', 'wpo_add_seo_tags');
function wpo_add_seo_tags() {
    if (is_admin()) {
        return;
    }

    $seo_title = get_option('wpo_seo_title');
    $seo_description = get_option('wpo_seo_description');
    $seo_keywords = get_option('wpo_seo_keywords');

    if ($seo_description) {
        echo '<meta name="description" content="' . esc_attr($seo_description) . '" />' . "\n";
    }
    if ($seo_keywords) {
        echo '<meta name="keywords" content="' . esc_attr($seo_keywords) . '" />' . "\n";
    }
    if (get_option('wpo_enable_schema')) {
        wpo_add_schema_org();
    }
}

function wpo_add_schema_org() {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => get_site_url(),
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
}

// Hook aktywacji
register_activation_hook(__FILE__, 'wpo_activation');
function wpo_activation() {
    update_option('wpo_minify_css', 1);
    update_option('wpo_minify_js', 1);
    update_option('wpo_lazy_loading', 1);
    update_option('wpo_defer_js', 1);
    update_option('wpo_cache_time', 3600);
    update_option('wpo_enable_schema', 1);
}

// Hook deaktywacji
register_deactivation_hook(__FILE__, 'wpo_deactivation');
function wpo_deactivation() {
    // Czyszczenie przy deaktywacji
    delete_option('wpo_minify_css');
    delete_option('wpo_minify_js');
    delete_option('wpo_lazy_loading');
    delete_option('wpo_defer_js');
    delete_option('wpo_cache_time');
    
    // Usuń zaplanowane eventy
    wp_clear_scheduled_hook('wpo_weekly_cleanup');
    wp_clear_scheduled_hook('wpo_process_ai_optimization');
}
?>
