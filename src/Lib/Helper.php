<?php

namespace FormItem\Ueditor\Lib;

use FormItem\Ueditor\Lib\Action\Context;

class Helper
{

    public static function parseUrl($url, $domain = 0, $url_prefix = '', $url_suffix = '', ?array $get_data = []):string{
        $parsed_url = $url;
        $get_data = $get_data ?: I("get.");


        if($url_prefix){
            $parsed_url = rtrim($url_prefix, '/') . $parsed_url;
        }

        if(self::isUploadImage($get_data['action']) && self::isImageFile($url) && $get_data['url_suffix']){
            $parsed_url = $parsed_url . $url_suffix;
        }

        if($get_data['urldomain']){
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

    public static function isImageFile($file_path): bool
    {
        $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'tiff', 'tif', 'heic', 'cr2', 'nef', 'arw'];

        $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

        return in_array($extension, $image_extensions, true);
    }

    public static function isUploadImage($action): bool
    {
        $action_list = [
            Context::ACTION_TYPE_UPLOAD_IMAGE,
            Context::ACTION_TYPE_UPLOAD_SCRAWL,
            Context::ACTION_TYPE_WX_CRAWLER,
            Context::ACTION_TYPE_CATCH_IMAGE
        ];

        return in_array($action, $action_list, true);
    }


}