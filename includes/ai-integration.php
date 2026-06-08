<?php
/**
 * AI Integration Module
 */

if (!defined('ABSPATH')) {
    exit;
}

// Funkcja do komunikacji z AI
function wpo_call_ai($prompt, $provider = null) {
    if (!$provider) {
        $provider = get_option('wpo_ai_provider', 'none');
    }

    if ($provider === 'none') {
        return false;
    }

    $api_key = get_option('wpo_ai_api_key');
    if (!$api_key) {
        return false;
    }

    switch ($provider) {
        case 'openai':
            return wpo_call_openai($prompt, $api_key);
        case 'claude':
            return wpo_call_claude($prompt, $api_key);
        case 'google':
            return wpo_call_google($prompt, $api_key);
        case 'cohere':
            return wpo_call_cohere($prompt, $api_key);
        default:
            return false;
    }
}

// OpenAI (ChatGPT)
function wpo_call_openai($prompt, $api_key) {
    $endpoint = 'https://api.openai.com/v1/chat/completions';
    
    $response = wp_remote_post($endpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'model' => 'gpt-3.5-turbo',
            'messages' => [[
                'role' => 'user',
                'content' => $prompt,
            ]],
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]),
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        wpo_log_ai_error('OpenAI', $response->get_error_message());
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['choices'][0]['message']['content'] ?? false;
}

// Anthropic (Claude)
function wpo_call_claude($prompt, $api_key) {
    $endpoint = 'https://api.anthropic.com/v1/messages';
    
    $response = wp_remote_post($endpoint, [
        'headers' => [
            'x-api-key' => $api_key,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ],
        'body' => json_encode([
            'model' => 'claude-2',
            'max_tokens' => 500,
            'messages' => [[
                'role' => 'user',
                'content' => $prompt,
            ]],
        ]),
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        wpo_log_ai_error('Claude', $response->get_error_message());
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['content'][0]['text'] ?? false;
}

// Google Gemini
function wpo_call_google($prompt, $api_key) {
    $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    
    $response = wp_remote_post($endpoint . '?key=' . $api_key, [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'contents' => [[
                'parts' => [[
                    'text' => $prompt,
                ]],
            ]],
        ]),
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        wpo_log_ai_error('Google Gemini', $response->get_error_message());
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['candidates'][0]['content']['parts'][0]['text'] ?? false;
}

// Cohere
function wpo_call_cohere($prompt, $api_key) {
    $endpoint = 'https://api.cohere.ai/v1/generate';
    
    $response = wp_remote_post($endpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'prompt' => $prompt,
            'max_tokens' => 500,
            'temperature' => 0.8,
        ]),
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        wpo_log_ai_error('Cohere', $response->get_error_message());
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['generations'][0]['text'] ?? false;
}

/**
 * Zaplanuj asynchroniczną optymalizację AI
 * 
 * @param int $post_id ID postu
 * @param object $post Obiekt postu
 */
function wpo_schedule_ai_optimization($post_id, $post) {
    // Pomiń, jeśli już jest w przetwarzaniu
    if (get_post_meta($post_id, '_wpo_ai_processing', true)) {
        return;
    }

    // Pomiń automatyczne zapisy
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Pomiń jeśli optymalizacja AI nie jest włączona
    if (!get_option('wpo_ai_seo_optimize')) {
        return;
    }

    // Ustaw flagę przetwarzania
    set_transient('wpo_ai_processing_' . $post_id, true, 300);

    // Zaplanuj asynchroniczne zadanie (lub użyj action-scheduler jeśli dostępny)
    wp_schedule_single_event(time() + 5, 'wpo_process_ai_optimization', [$post_id]);

    // Upewnij się, że cron uruchomi się
    spawn_cron();
}

/**
 * Obsługa asynchronicznej optymalizacji AI
 * 
 * @param int $post_id ID postu
 */
function wpo_process_ai_optimization($post_id) {
    // Sprawdź czy już jest w przetwarzaniu
    if (get_transient('wpo_ai_processing_' . $post_id)) {
        return;
    }

    // Ustaw flagę przetwarzania
    set_transient('wpo_ai_processing_' . $post_id, true, 300);

    try {
        $post = get_post($post_id);
        
        if (!$post) {
            return;
        }

        $title = $post->post_title;
        $content = $post->post_content;
        
        $prompt = "Optymalizuj poniższą treść pod SEO:\n\nTytuł: $title\n\nTreść: $content";
        
        $optimized = wpo_call_ai($prompt);
        
        if ($optimized && $optimized !== $content) {
            // Zablokuj rekurencję poprzez flagę
            update_post_meta($post_id, '_wpo_ai_optimizing', true);
            
            wp_update_post([
                'ID' => $post_id,
                'post_content' => $optimized,
            ]);
            
            // Usunięcie flagi po aktualizacji
            delete_post_meta($post_id, '_wpo_ai_optimizing');
            
            // Loguj sukces
            wpo_log_ai_success($post_id, 'Treść zoptymalizowana');
        }
    } catch (Exception $e) {
        wpo_log_ai_error('Processing', $e->getMessage());
    } finally {
        // Usuń flagę przetwarzania
        delete_transient('wpo_ai_processing_' . $post_id);
    }
}

/**
 * Hook do planowania zadań optymalizacji AI
 * Zamiast bezpośredniego wywoływania, planujemy asynchroniczne zadanie
 */
add_action('wp_insert_post', 'wpo_schedule_ai_optimization', 10, 2);

/**
 * Zarejestruj cron job
 */
add_action('wpo_process_ai_optimization', 'wpo_process_ai_optimization', 10, 1);

/**
 * Zaloguj błędy AI
 */
function wpo_log_ai_error($provider, $message) {
    $log = get_option('wpo_ai_error_log', []);
    $log[] = [
        'timestamp' => current_time('mysql'),
        'provider' => $provider,
        'message' => $message,
    ];
    // Przechowuj ostatnie 100 błędów
    $log = array_slice($log, -100);
    update_option('wpo_ai_error_log', $log);
}

/**
 * Zaloguj sukces AI
 */
function wpo_log_ai_success($post_id, $message) {
    $log = get_option('wpo_ai_success_log', []);
    $log[] = [
        'timestamp' => current_time('mysql'),
        'post_id' => $post_id,
        'message' => $message,
    ];
    // Przechowuj ostatnie 100 sukcesów
    $log = array_slice($log, -100);
    update_option('wpo_ai_success_log', $log);
}

/**
 * Wyczyść logi AI jeśli się urośli
 */
add_action('admin_init', 'wpo_cleanup_ai_logs');
function wpo_cleanup_ai_logs() {
    if (!get_transient('wpo_cleanup_ai_logs_daily')) {
        delete_option('wpo_ai_error_log');
        delete_option('wpo_ai_success_log');
        set_transient('wpo_cleanup_ai_logs_daily', true, DAY_IN_SECONDS);
    }
}

?>
