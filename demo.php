<?php

/**
 * @Author: Tekin
 * @Date:   2020-06-05 21:12:00
 * @Last Modified 2020-06-05
 */
// include_once __DIR__ . '/test_helper.php'; // Tekin 专用调试函数库， just for dev

require_once __DIR__ . '/vendor/autoload.php';

$str = "http://dev.tekin.cn";
// 直接生成并输出二维码图片，默认大小
// \tekintian\TekinQR::getQRImg($str);

// 返回base64图片流
$qr = \tekintian\TekinQR::getQRImg($str, 10, null, 1);
echo $qr;

// 生成带loog的二维码
// $qr = \tekintian\TekinQR::getQRImg($str, 10, "http://tekin.cn/logo.png", 0);

// 生成二维码并写入到  /var/www/static/qr/123.png
// $qr =\tekintian\TekinQR::getQRImg($str, 10, "http://tekin.cn/logo.png", 2, "/var/www/static/qr/123.png");
