<?php

declare(strict_types=1);

use Yansongda\Pay\Pay;

return [
    'alipay' => [
        'default' => [
            // 必填-支付宝分配的 app_id
            'app_id' => '2021004xxxx',
            // 必填-应用私钥 字符串或路径
            'app_secret_cert' => 'MIIE9w0BAQEFAASCBKcwggSjAgEAAoIBAQDVRVFQ/xNhtmhueiIlstB6K/5B5oLkGCniYXzHBTfqzC167397fWrhpIk/V52ihYY1PGv9wi/fezJbtEYcGtjLKSFCzJbt9knnPYRSjGd0ttQNdHd0oQPwcPBq0GsFa0IFVQ3XACF8vTjcxId8rT/oPXBXLEikic7gUnCIhYkZBOQdHBWNqAxpjAZZBhCvpdBA63NJkxcHzPuE71Ewc0565jtc7dMcNThgdOMLPc+jlPQSwcE2Y+PQ2ZRn7JwBKbvAKNj0pni9CLooJxnIh7+wMnijMboH3HIrmzCPJ4AI5EBu0KFmJWP/Nssu8ax/8MX6ejczCdbCDVcUDgcxIe91AgMBAAECggEAbuCS3iE+2lYTBRzKE/9bE6k7jtdgxWUqoV89l9rGr3NnUbqh9HnHz3fTQvMyKQRyOcsn+c2zReSu0a5vpLzwaO8BM9FZxm19DpOU77W/M5a5uCUM1b3AZojQ9TdV7INO16bYgya2Qju+DEjAh7kquHr4pNmJCsH6o/U4PEHwfOhy0VH7M6dWUFqtmsP+5uitlL6oz7EEe1J+OAFpZvcy5X93h8fjGiHyORaD5V6aD4pD1dQuYP2+ooxhCQo78LxMmVdfYD4IfbUoDHno4JkCrBiEkPo6t6grWNNgzdxZrKvPGtssC4Ho406vuQCg4sQWd02rpBAYEsTMBRJxXGMOAQKBgQD1RiulyIxSF1V9uFpWp5U4u9xQAC8BhynfOot9zm1Sbmuq+50F64qBTrAOI/IBJyFlQb3xLdvCvjnmRFvYlojf7lI9RwCEfxyuUu5dkx9YfZhjHYWlIGL48PaGOGxJfSukDeTumtE+P88/7vp5zSL+gWS8bc8GnjglK5HUctPg5QKBgQDemN8m0Qz8j/8hJA4NBNJ3cQLk6C68oVf2w7DQQSPIvceVrOAyk789RI35x9dC7ecexkFkR+orDGj0fBR7kKzHs4OX9FrQROmJ3fuQq+03nSZAP2o8UiT/za1PBoIsj2gZDXlC0q1f9ACOYLjKr6QOEwyugqmAPCPTSLrITQc7UQKBgQDh4lHoSB4SrDKrqdBo5cVjjn+DneesfJJvZzd4EVhVBiRaP4eJIDdahuFU5H3H2gXDfxwytY0ieJZSHrqHaXt837sVfAJaA8aiNPYZb5j6ohBpl5KxVHZR1Xj0e5oexAwg9jrUE/iIX7O6qZg7/FQyF3ByqJ509dm3Qbz37xh6kQKBgAqibQ7Sr/ck/gBcU5uFnnR8XrIG8ayrXKN6Z+kbI5WEk5NwBeoEqv9HVi0Xwg39hawvtpIO6X4TArSjdOsOV2LXDbNlxizrDek7RLh9rkCY7mnlXbLyDbh24A/FdNSiKUwBG8j4fbX210v2DP4J9CGEAXgY3/YzaVa/w8SsAWtxAoGASmm2g+P0GrITcEa695MnCQ/m3boKBNII80trwDsT5weyoIHUiup0xJU3AoySB8vfvCu67erydS/mNyTuR+n62pQy33iBYgnmTHoiByH11yisKzcqu/RU/qM6vuZwcj92BfkOLaRTVtnSdPsYo3etKVsUT8ke5PvBpNGbziOqU4E=',
            // 必填-应用公钥证书 路径
            'app_public_cert_path' => storage_path('app/certs/appCertPublicKey_20210xxxx.crt'),
            // 必填-支付宝公钥证书 路径
            'alipay_public_cert_path' => storage_path('app/certs/alipayCertPublicKey_RSA2.crt'),
            // 必填-支付宝根证书 路径
            'alipay_root_cert_path' => storage_path('app/certs/alipayRootCert.crt'),
            'return_url' => 'https://xx.xx/user',
            'notify_url' => 'https://xxx.xx/api/home/shop/alipay_callback',
            // 选填-服务商模式下的服务商 id，当 mode 为 Pay::MODE_SERVICE 时使用该参数
            'service_provider_id' => '',
            // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SANDBOX, MODE_SERVICE
            'mode' => Pay::MODE_NORMAL,
        ],
    ],
    'wechat' => [
        'default' => [
            // 必填-商户号，服务商模式下为服务商商户号
            'mch_id' => '1671050768',
            // 选填-v2商户私钥
            'mch_secret_key_v2' => '',
            // 必填-商户秘钥
            'mch_secret_key' => '7d4fce4f5ab3d07xx',
            // 必填-商户私钥 字符串或路径
            'mch_secret_cert' => storage_path('app/certs/apiclient_key.pem'),
            // 必填-商户公钥证书路径
            'mch_public_cert_path' => storage_path('app/certs/apiclient_cert.pem'),
            // 必填
            'notify_url' => 'https://xx.xx/shop/alipay_callback',
            // 选填-公众号 的 app_id
            'mp_app_id' => 'xxxxxxxxx',
            // 选填-小程序 的 app_id
            'mini_app_id' => '',
            // 选填-app 的 app_id
            'app_id' => '',
            // 选填-服务商模式下，子公众号 的 app_id
            'sub_mp_app_id' => '',
            // 选填-服务商模式下，子 app 的 app_id
            'sub_app_id' => '',
            // 选填-服务商模式下，子小程序 的 app_id
            'sub_mini_app_id' => '',
            // 选填-服务商模式下，子商户id
            'sub_mch_id' => '',
            // 选填-微信公钥证书路径, optional，强烈建议 php-fpm 模式下配置此参数
            'wechat_public_cert_path' => [
          //      '45F59D4DABF31918AFCEC556D5D2C6E376675D57' => __DIR__.'/Cert/wechatPublicKey.crt',
            ],
            // 选填-默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE
            'mode' => Pay::MODE_NORMAL,
        ],
    ],
    'unipay' => [
        'default' => [
            // 必填-商户号
            'mch_id' => '',
            // 选填-商户密钥：为银联条码支付综合前置平台配置：https://up.95516.com/open/openapi?code=unionpay
            'mch_secret_key' => '979da4cfccbae7923641daa5dd7047c2',
            // 必填-商户公私钥
            'mch_cert_path' => '',
            // 必填-商户公私钥密码
            'mch_cert_password' => '000000',
            // 必填-银联公钥证书路径
            'unipay_public_cert_path' => '',
            // 必填
            'return_url' => '',
            // 必填
            'notify_url' => '',
        ],
    ],
    'http' => [ // optional
        'timeout' => 5.0,
        'connect_timeout' => 5.0,
        // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
    ],
    // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
    'logger' => [
        'enable' => false,
        'file' => null,
        'level' => 'debug',
        'type' => 'single', // optional, 可选 daily.
        'max_file' => 30,
    ],
];
