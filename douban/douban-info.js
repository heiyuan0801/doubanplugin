(function ($) {
    tinymce.create('tinymce.plugins.DoubanInfo', {
        init: function (ed, url) {
            ed.addButton('douban_info_button', {
                title: '豆瓣id',
                image: url + '/img/douban.png', // Provide an icon for the button
                onclick: function () {
                    // Open a window with a form to input Douban info
                    var win = ed.windowManager.open({
                        title: '豆瓣id',
                        body: [
                            { type: 'textbox', name: 'doubanId', label: 'Douban ID' }
                        ],
                        onsubmit: function (e) {
                            // Handle the form submission and insert the Douban info into the editor
                            var doubanId = e.data.doubanId;
                            console.log('Douban ID:', doubanId); // Debugging: Log the豆瓣id

                            fetch("https://你的自建豆瓣地址/db.php?id=" + doubanId, {
                                "body": null,
                                "method": "GET"
                            }).then(response => {
                                if (response.ok) {
                                    return response.text(); // 使用 text() 方法获取原始响应文本
                                } else {
                                    throw new Error('Network response was not ok.');
                                }
                            }).then(text => {
                                // 移除回调函数部分，只保留 JSON 数据
                                const jsonText = text.replace(/^[^\(]*\(|\);?$/g, '');
                                const data = JSON.parse(jsonText); // 解析 JSON 数据
                                console.log(data); // 这里的 data 是解析后的响应数据

                                // 上传图片到媒体库
                                jQuery.ajax({
                                    url: ajaxurl, // WordPress 提供的全局变量
                                    type: 'POST',
                                    data: {
                                        action: 'upload_image_to_media',
                                        image_url: data.data.vod_pic
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            console.log('Image uploaded successfully:', response.data.url);
                                            // 在编辑器中插入图片
                                            var content = '<div class="douban-info">';
                                            content += '<h2>' + data.data.vod_name + '</h2>';
                                            content += '<img src="' + response.data.url + '" alt="' + data.data.vod_name + '">';
                                            content += '<p class="rating">评分: ' + data.data.vod_score + '</p>';
                                            content += '<p>年份: ' + data.data.vod_year + '</p>';
                                            content += '<p>语言: ' + data.data.vod_lang + '</p>';
                                            content += '<p>地区: ' + data.data.vod_area + '</p>';
                                            content += '<p>类型: ' + data.data.vod_class + '</p>';
                                            content += '<p>导演: ' + data.data.vod_director + '</p>';
                                            content += '<p>编剧: ' + data.data.vod_writer + '</p>';
                                            content += '<p>主演: ' + data.data.vod_actor + '</p>';
                                            content += '<p>上映日期: ' + data.data.vod_pubdate + '</p>';
                                            content += '<p>片长: ' + data.data.vod_duration + ' 分钟</p>';
                                            content += '<p>总集数: ' + data.data.vod_total + '</p>';
                                            content += '<p>备注: ' + data.data.vod_remarks + '</p>';
                                            content += '<p>标签: ' + data.data.vod_tag + '</p>';
                                            content += '<p>豆瓣评分: ' + data.data.vod_douban_score + '</p>';
                                            content += '<p>评分人数: ' + data.data.vod_score_num + '</p>';
                                            content += '<p>总评分: ' + data.data.vod_score_all + '</p>';
                                            content += '<p>豆瓣ID: ' + data.data.vod_douban_id + '</p>';
                                            content += '<p>简介: ' + data.data.vod_content + '</p>';
                                            content += '<p><a href="' + data.data.vod_reurl + '" target="_blank">查看豆瓣详情</a></p>';
                                            content += '</div>';
                                            ed.insertContent(content);
                                        } else {
                                            console.error('Error:', response.data);
                                        }
                                    },
                                    error: function(error) {
                                        console.error('AJAX error:', error);
                                    }
                                });

                            }).catch(error => {
                                console.error('There has been a problem with your fetch operation:', error);
                            });
                        }
                    });
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });

    // Register the plugin
    tinymce.PluginManager.add('douban_info_button', tinymce.plugins.DoubanInfo);
})(jQuery);
