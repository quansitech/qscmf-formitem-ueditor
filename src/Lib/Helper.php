<?php

namespace FormItem\Ueditor\Lib;

class Helper
{

    public static function parseUrl($url, $domain = 0, $url_prefix = '', $url_suffix = ''):string{
        $parsed_url = $url;

        if($url_prefix){
            $parsed_url = rtrim($url_prefix, '/') . $parsed_url;
        }

        if($_GET['url_suffix']){
            $parsed_url = $parsed_url . $url_suffix;
        }

        if($_GET['urldomain']){
            $parsed_url = HTTP_PROTOCOL . '://' . SITE_URL . $url;
        }

        return $parsed_url;
    }

    public static function parseCatchRes(string $source, array $file_info, &$res):void{

        $res["state"] = $file_info["state"];
        $res["url"] = $file_info["url"];
        $res["size"] = $file_info["size"];
        $res["title"] = htmlspecialchars($file_info["title"]);
        $res["original"] = htmlspecialchars($file_info["original"]);
        $res["source"] = htmlspecialchars_decode($source);

    }

}