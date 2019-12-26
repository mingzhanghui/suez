<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 12/17/2019
 * Time: 16:51
 */

namespace app\utils;


class Crypt {
    private static $key = "\x8a\xbe\x1e\x3f\xab\xb0\x90\xe8\x7d\x47\xae\xbd\x47\xd4\xce\x7d\xe4\x6a\xaa\x12\x85\xd8\xbc\x11";
    private static $iv = "\x8a\xbe\x1e\x3f\xab\xb0\x90\xe8";

    public static function encrypt($input) {
        if (!$input || empty($input)) {
            return null;
        }
        $key = self::$key;
        $iv = self::$iv;
        $data = openssl_encrypt($input,
            "DES-EDE3-OFB",
            $key,
            OPENSSL_RAW_DATA| OPENSSL_NO_PADDING, $iv);

        return base64_encode($data);
    }

    public static function decrypt($cypher) {
        if (!$cypher || empty($cypher)) {
            return null;
        }
        $input = base64_decode($cypher);

        $key = self::$key;
        $iv = self::$iv;
        $plain = openssl_decrypt($input,
            "DES-EDE3-OFB",
            $key,
            OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);

        return $plain;
    }
}