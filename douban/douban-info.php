<?php
/**
 * Plugin Name: 豆瓣信息插入
 * Plugin URI: https://github.com/heiyuan0801/doubanplugin
 * Description: 把豆瓣信息插入到你的文章中
 * Version: 1.2
 * Author: 爱云
 * Author URI: https://blog.freeimg.cn/
 */

// Ensure the classic editor plugin is loaded
if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

// Enqueue our custom script for the backend
function classic_editor_douban_enqueue_scripts() {
    wp_enqueue_script('jquery'); // Ensure jQuery is loaded
    wp_enqueue_script('classic-editor-douban-info-script', plugins_url('douban-info.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('classic-editor-douban-info-script', 'doubanInfoAjax', array('ajaxUrl' => admin_url('admin-ajax.php')));
}
add_action('admin_enqueue_scripts', 'classic_editor_douban_enqueue_scripts');

// Add a button to the TinyMCE editor
function classic_editor_add_douban_button($plugins) {
    $plugins['douban_info_button'] = plugins_url('douban-info.js', __FILE__);
    return $plugins;
}
add_filter('mce_external_plugins', 'classic_editor_add_douban_button');

// Add a button to the TinyMCE editor toolbar
function classic_editor_add_douban_button_to_toolbar($buttons) {
    array_push($buttons, 'douban_info_button');
    return $buttons;
}
add_filter('mce_buttons', 'classic_editor_add_douban_button_to_toolbar');

// Handle AJAX requests
function classic_editor_douban_info_ajax_handler() {
    $id = sanitize_text_field($_POST['id']);
    // 调用豆瓣信息获取脚本
    $douban_data = get_douban_data($id);

    if ($douban_data) {
        // 保存图片到本地
        $local_image_path = save_image_to_local($douban_data['vod_pic']);
        if ($local_image_path) {
            $douban_data['vod_pic'] = $local_image_path;
        }

        echo json_encode(array('success' => true, 'data' => $douban_data));
    } else {
        echo json_encode(array('success' => false, 'message' => '无法获取豆瓣信息，请检查 ID 是否正确。'));
    }
    wp_die();
}
add_action('wp_ajax_douban_info', 'classic_editor_douban_info_ajax_handler');

// 获取豆瓣信息的函数
function get_douban_data($id) {
    // 调用豆瓣 API
    $url = 'http://149.88.68.226/db.php?id=' . $id; // 确保这个 URL 是正确的
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        error_log('WP Remote Get Error: ' . $response->get_error_message()); // Debugging: Log any WP Remote Get errors
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Debugging: Log the entire response body
    error_log('Response Body: ' . $body); // Log the raw response for debugging

    if (isset($data['code']) && $data['code'] == 1 && isset($data['data'])) {
        return $data['data'];
    }

    error_log('Invalid data received: ' . print_r($data, true)); // Log invalid responses
    return false;
}

// 保存图片到本地的函数
function save_image_to_local($url) {
    $upload_dir = wp_upload_dir();
    $upload_path = $upload_dir['path'];
    $upload_url = $upload_dir['url'];

    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        error_log('Image Download Error: ' . $response->get_error_message());
        return false;
    }

    $image_data = wp_remote_retrieve_body($response);
    $filename = basename($url);
    $file_path = $upload_path . '/' . $filename;

    if (file_put_contents($file_path, $image_data)) {
        return $upload_url . '/' . $filename;
    } else {
        error_log('File Write Error: Failed to write image to disk.');
        return false;
    }
}