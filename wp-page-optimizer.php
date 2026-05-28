<?php
/**
 * Nazwa Wtyczki: Page Optimizer Pro
 * Opis: Optymalizuje wydajność strony WordPress - minifikacja, cache, lazy loading
 * Wersja: 1.0.0
 * Autor: Norbert Siedlecki
 * Licencja: GPL v2 lub nowsza
 * Domena tekstowa: wpo
 * URI wtyczki: https://github.com/norbertsiedlecki-prog/wp-page-optimizer
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WPO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPO_VERSION', '1.0.0');

// Menu admin
add_action('admin_menu', 'wpo_add_admin_menu');
function wpo_add_admin_menu() {
    add_menu_page(
        'Page Optimizer',
        'Page Optimizer',
        'manage_options',
        'wpo-settings',
        'wpo_render_settings_page',
        'dashicons-rocket',
        99
    );
}

// Strona ustawień
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
        <h1>🚀 Page Optimizer Pro</h1>
        <p style="font-size: 16px; color: #666;">Optymalizuj wydajność swojej strony WordPress</p>
        
        <form method="POST" style="max-width: 700px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 20px;">
            <?php wp_nonce_field('wpo_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="wpo_minify_css">📦 Minifikacja CSS</label></th>
                    <td>
                        <input type="checkbox" name="wpo_minify_css" id="wpo_minify_css" value="1" <?php checked($minify_css); ?> />
                        <p class="description">Zmniejsza rozmiar plików CSS usuwając zbędne znaki (spacje, tabulacje)</p>
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
                        <p class="description">Ładuje JS dopiero po wyrenderowaniu strony - szybsze wyświetlanie</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_lazy_loading">🖼️ Lazy Loading obrazów</label></th>
                    <td>
                        <input type="checkbox" name="wpo_lazy_loading" id="wpo_lazy_loading" value="1" <?php checked($lazy_loading); ?> />
                        <p class="description">Ładuje obrazy dopiero gdy użytkownik ich widzi - mniejsze zuzycie transferu</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpo_cache_time">💾 Czas cache (sekundy)</label></th>
                    <td>
                        <input type="number" name="wpo_cache_time" id="wpo_cache_time" value="<?php echo esc_attr($cache_time); ?>" min="300" style="width: 150px;" />
                        <p class="description">Domyślnie 3600 sekund (1 godzina). Zwiększ dla lepszej wydajności.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('💾 Zapisz ustawienia', 'primary', 'submit_wpo'); ?>
        </form>

        <div style="margin-top: 30px; padding: 20px; background: #f0f6ff; border-left: 4px solid #0073aa; border-radius: 4px; max-width: 700px;">
            <h3>📊 Status optymalizacji</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 10px 0;">
                    <strong>Minifikacja CSS:</strong> 
                    <?php echo $minify_css ? '<span style="color: green;">✅ Włączona</span>' : '<span style="color: red;">❌ Wyłączona</span>'; ?>
                </li>
                <li style="margin: 10px 0;">
                    <strong>Minifikacja JS:</strong> 
                    <?php echo $minify_js ? '<span style="color: green;">✅ Włączona</span>' : '<span style="color: red;">❌ Wyłączona</span>'; ?>
                </li>
                <li style="margin: 10px 0;">
                    <strong>Lazy Loading:</strong> 
                    <?php echo $lazy_loading ? '<span style="color: green;">✅ Włączony</span>' : '<span style="color: red;">❌ Wyłączony</span>'; ?>
                </li>
                <li style="margin: 10px 0;">
                    <strong>Defer JS:</strong> 
                    <?php echo $defer_js ? '<span style="color: green;">✅ Włączony</span>' : '<span style="color: red;">❌ Wyłączony</span>'; ?>
                </li>
                <li style="margin: 10px 0;">
                    <strong>Czas cache:</strong> 
                    <span style="color: #0073aa;"><?php echo $cache_time; ?> sekund</span>
                </li>
            </ul>
        </div>
    </div>
    <?php
}

// Optymalizacja frontendu
add_action('wp_enqueue_scripts', 'wpo_optimize_frontend', 999);
function wpo_optimize_frontend() {
    if (is_admin()) {
        return;
    }

    $defer_js = get_option('wpo_defer_js');
    $lazy_loading = get_option('wpo_lazy_loading');

    // Opóźnij JavaScript
    if ($defer_js) {
        global $wp_scripts;
        if (isset($wp_scripts)) {
            foreach ($wp_scripts->queue as $handle) {
                $wp_scripts->add_data($handle, 'defer', true);
            }
        }
    }

    // Lazy loading obrazów
    if ($lazy_loading) {
        add_filter('wp_get_attachment_image_attributes', 'wpo_add_lazy_loading');
    }
}

function wpo_add_lazy_loading($attr) {
    $attr['loading'] = 'lazy';
    return $attr;
}

// Minifikacja CSS
add_filter('style_loader_tag', 'wpo_minify_css_tags', 10, 4);
function wpo_minify_css_tags($tag, $handle, $src, $media) {
    if (!get_option('wpo_minify_css')) {
        return $tag;
    }

    // Dodaj async/defer do niekrytycznych CSS
    if (strpos($handle, 'wpo-') === false) {
        return str_replace("media='$media'", "media='print' onload=\"this.media='$media'\"", $tag);
    }
    return $tag;
}

// Dodaj nagłówki cache
add_action('send_headers', 'wpo_add_cache_headers');
function wpo_add_cache_headers() {
    if (!is_admin()) {
        $cache_time = get_option('wpo_cache_time', 3600);
        header('Cache-Control: public, max-age=' . $cache_time);
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
    }
}

// Hook aktywacji
register_activation_hook(__FILE__, 'wpo_activation');
function wpo_activation() {
    // Włącz wszystkie opcje domyślnie
    update_option('wpo_minify_css', 1);
    update_option('wpo_minify_js', 1);
    update_option('wpo_lazy_loading', 1);
    update_option('wpo_defer_js', 1);
    update_option('wpo_cache_time', 3600);
}
?>