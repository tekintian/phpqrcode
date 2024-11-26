<?php

/**
 * @Author: Tekin
 * @Date:   2020-06-05 21:12:00
 * @Last Modified 2023-11-06
 */
// include_once __DIR__ . '/test_helper.php'; // Tekin 专用调试函数库， just for dev

require_once __DIR__ . '/vendor/autoload.php';

$str = "http://dev.tekin.cn";
// 直接生成并输出二维码图片，默认大小
// \tekintian\TekinQR::getQRImg($str);

// 返回base64图片数据
// $qr = \tekintian\TekinQR::getQRImg($str, 10, '', 1);
// echo $qr;

// 生成带loog的二维码并直接输出到浏览器
$qr = \tekintian\TekinQR::getQRImg($str, 10, "http://tekin.cn/logo.png", 0);

// 生成二维码并写入到  /var/www/static/qr/123.png
// $qr =\tekintian\TekinQR::getQRImg($str, 10, "http://tekin.cn/logo.png", 2, "/var/www/static/qr/123.png");

// 生成二维码并返回二进制二维码图片数据
// $img_data = \tekintian\TekinQR::getQRImg($str, 10, '', 3);
// laravel 写入图片数据
// Storage::disk('local')->put('myqrfile.png', $img_data);
// 将二进制图片数据写入到文件
// file_put_contents('myqrfile.png', $img_data);
