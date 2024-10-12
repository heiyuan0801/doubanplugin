# doubanplugin
wordpress豆瓣插![PixPin_2024-10-12_15-25-36](https://github.com/user-attachments/assets/677c0f5b-9264-4c9b-9603-a864b9bf1444)
入信息插件   
在你的主题的functions.php中添加以下代码：
add_action('wp_ajax_upload_image_to_media', 'upload_image_to_media');
add_action('wp_ajax_nopriv_upload_image_to_media', 'upload_image_to_media');

function upload_image_to_media() {
    if (!current_user_can('upload_files')) {
        wp_send_json_error('You do not have permission to upload files.');
        return;
    }

    $image_url = esc_url_raw($_POST['image_url']);

    if (empty($image_url)) {
        wp_send_json_error('Image URL is required.');
        return;
    }

    // 使用 WordPress 函数下载并保存图片
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $media_id = media_sideload_image($image_url, 0, null, 'id');

    if (is_wp_error($media_id)) {
        wp_send_json_error('Failed to download image.');
    } else {
        wp_send_json_success(['media_id' => $media_id, 'url' => wp_get_attachment_url($media_id)]);
    }
}

需要自己自建一个api文件 db.php 上传服务器 即可

#调用方式 http://你的网站域名/db.php?id=35230876

文件在

https://github.com/heiyuan0801/doubanplugin

压缩包下载 上传插件就可以了。


