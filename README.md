# php qrcode 二维码生成工具  base64图片二维码

php生成带LOGO的二维码图片, 支持自定义LOGO，自定义输出目录和自定义返回类型，静态方法调用，方便快捷，高效，简洁的PHP二维码生成工具

支持二维码直接返回符合 RFC 2045规范 的 base64, b64 二维码, 首创方法！

支持PHP版本： 5.x -- 7.4, 推荐php7中使用

## 使用方法

~~~shell
# 切换至项目根目录后执行以下命令安装本工具

composer require tekintian/phpqrcode

~~~


## 生成二维码 封装工具类使用方法
- 推荐方式， 只支持png
~~~php
# autoload.php自动载入
require_once __DIR__ . 'vendor/autoload.php';

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

// getQRImg(String $str, int $size = 10, String $logo = null, int $ret_type = 0, String $out_file = null)
~~~

## phpqrcode原生工具类使用方法
- 官方模式, 可支持png, jpg格式图片

~~~php
# autoload.php自动载入
require_once __DIR__ . '/vendor/autoload.php';

//生成PNG图片
\tekintian\QRcode::png($str, false, 3, 10, 1, 4, 1);
//生成JPG图片
// \tekintian\QRcode::jpg($str, false, 3, 10, 1, 4, 1);

~~~

更多用法，请参考官方文档 http://phpqrcode.sourceforge.net/




