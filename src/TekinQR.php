<?php
namespace tekintian;

//载入QR核心类库
include_once __DIR__ . '/QRcode.php';

/**
 * 字符串生成二维码工具类，支持自定义LOGO， 自定义输出目录和自定义返回类型
 * @Author: Tekin
 * @Date:   2020-06-05 19:58:19
 * @Last Modified 2024-11-26
 */
class TekinQR
{
    /**
     * 将字符串生成二维码图片
     * @param  string      $str      [description]
     * @param  int|integer $size     [description]
     * @param  string|null $logo     [二维码LOGO图片，可以是本地图片，或者网络图片]
     * @param  int|integer $ret_type [二维码返回类型 默认 0 直接输出图片到浏览器； 1 返回base64字符串； 2 写入文件路，必须提供$out_file ]; 3 返回二进制图片数据
     * @param  string|null $out_file [需要写入的二维码图片路径，必须提供完整的图片路径， 如  /var/www/static/qr/01.png ]
     * @return [type]                [description]
     */
    public static function getQRImg(string $str, int $size = 10, string $logo = '', int $ret_type = 0, string $out_file = '')
    {
        $logo = empty($logo) ? '' : $logo;
        //如果logo非网络图片地址，则增加默认路径
        if ($logo && false === strpos($logo, 'http')) {
            // 如果logo图片不存在，则重置为null
            if (!is_file($logo)) {
                $logo = '';
            }
        } else if (false !== strpos($logo, 'http')) {
            //远程logo， 但是logo不存在，则将logo重置为空
            if (!self::isValidHttpImg($logo)) {
                $logo = '';
            }
        } else {
            $logo = '';
        }

        ob_start(); //开启缓存
        //生成二维码 纠错级别：L、M、Q、H 点的大小：1到10
        QRcode::png($str, false, QR_ECLEVEL_H, $size, 1, 4, 1);

        //由于phpQrcode类直接返回到浏览器，所以需要利用php缓冲器阻止他直接返回到浏览器，然后捕捉到二维码的图片流
        $qrcode = ob_get_contents(); // Return the contents of the output buffer
        ob_end_clean(); //清除缓存

        //从qr流中生成图片
        $qrcode = imagecreatefromstring($qrcode);
        if (!$qrcode) {
            return;
        }

        //合成logo
        if ($logo) {
            $qrcode_width = imagesx($qrcode);
           // $qrcode_height = imagesy($qrcode);

            $logo = imagecreatefromstring(file_get_contents($logo));
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);

            //计算logo图片的宽高及相对于二维码的摆放位置,将logo放到二维码中央
            $logo_qr_height = $logo_qr_width = $qrcode_width / 5 - 6;
            $from_width = ($qrcode_width - $logo_qr_width) / 2;
            //合成二维码和logo
            imagecopyresampled($qrcode, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }

        $ret_qr = null; // 返回的QR定义
        // 定义一个临时文件
        $_tmp_qr_file = tempnam(sys_get_temp_dir(), 'qr');
        // 根据不同返回类型做相应的处理，默认返回图片流
        switch ($ret_type) {
            case 0: // 直接输出图片到浏览器
                header('Content-type: image/png');
                imagepng($qrcode); // 向浏览器输出图片
                break;
            case 1: // 返回base64数据流
                imagepng($qrcode, $_tmp_qr_file); // 先写入图片到临时文件夹，然后在使用 file_get_contents 读出后转换为base64
                // 先写入图片到临时文件夹，然后在使用 file_get_contents 读出后转换为base64
                $ret_qr = 'data:png;base64,' . chunk_split(base64_encode(file_get_contents($_tmp_qr_file)));
                unlink($_tmp_qr_file); // 删除临时文件
                break;
            case 2: // 写入到指定路径，
                // 如果未给定输出路径，则返回base64数据流
                if ($out_file) {
                    imagepng($qrcode, $out_file); //向浏览器输出图片
                    $ret_qr = $out_file;
                } else {
                    imagepng($qrcode, $_tmp_qr_file); // 先写入图片到临时文件夹，然后在使用 file_get_contents 读出后转换为base64
                    //使用 RFC 2045规范转base64
                    $ret_qr = 'data:png;base64,' . chunk_split(base64_encode(file_get_contents($_tmp_qr_file)));
                    unlink($_tmp_qr_file); // 删除临时文件
                }
                break;
            case 3: // 返回二进制图片数据
                imagepng($qrcode, $_tmp_qr_file); // 先写入图片到临时文件夹，然后在使用 file_get_contents 读出后返回二进制数据
                // 先写入图片到临时文件夹，然后在使用 file_get_contents 读出后转换为base64
                $ret_qr = file_get_contents($_tmp_qr_file);
                unlink($_tmp_qr_file); // 删除临时文件
                break;
            default:
                header('Content-type: image/png');
                imagepng($qrcode); // 向浏览器输出图片
                break;
        }
        //销毁对象
        imagedestroy($qrcode);
        $logo && imagedestroy($logo);

        //如果需要返回，则直接return
        if ($ret_qr) {
            return $ret_qr;
        }
        exit; //防止继续向下渲染
    }
    /**
     * 判断远端文件是否有效
     * @param $url 文件地址
     * @return bool
     */
    public static function isValidHttpImg($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (curl_exec($ch) !== false) {
            return true;
        } else {
            return false;
        }
    }

}
