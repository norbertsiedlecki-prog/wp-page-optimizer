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
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['generations'][0]['text'] ?? false;
}

// Hook do generowania treści
add_action('wp_insert_post', 'wpo_ai_optimize_content', 10, 2);
function wpo_ai_optimize_content($post_id, $post) {
    if (get_option('wpo_ai_seo_optimize') && !defined('DOING_AUTOSAVE')) {
        $title = $post->post_title;
        $content = $post->post_content;
        
        $prompt = "Optymalizuj poniższą treść pod SEO:\n\nTytuł: $title\n\nTreść: $content";
        
        $optimized = wpo_call_ai($prompt);
        
        if ($optimized) {
            wp_update_post([
                'ID' => $post_id,
                'post_content' => $optimized,
            ]);
        }
    }
}
?>