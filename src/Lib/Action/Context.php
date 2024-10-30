<?php

namespace FormItem\Ueditor\Lib\Action;

class Context
{

    public const ACTION_TYPE_CONFIG = 'config';
    public const ACTION_TYPE_UPLOAD_IMAGE = 'uploadimage';
    public const ACTION_TYPE_UPLOAD_SCRAWL = 'uploadscrawl';
    public const ACTION_TYPE_UPLOAD_VIDEO = 'uploadvideo';
    public const ACTION_TYPE_UPLOAD_FILE = 'uploadfile';
    public const ACTION_TYPE_WX_CRAWLER = 'get_wx_rich_text';
    public const ACTION_TYPE_LIST_FILE = 'listfile';
    public const ACTION_TYPE_LIST_IMAGE = 'listimage';
    public const ACTION_TYPE_CATCH_IMAGE = 'catchimage';

    public static function genActionByType(string $action_type, array $get_data):AAction{
        return self::genByType($action_type, $get_data);
    }

    private static function genByType(string $action_type, array $get_data):AAction{
        $get_data = $get_data ? $get_data : I("get.");
        $post_data = I('post.')  ?? [];

        return match ($action_type) {
            self::ACTION_TYPE_CONFIG => new ConfigAction($get_data),
            self::ACTION_TYPE_UPLOAD_IMAGE, self::ACTION_TYPE_UPLOAD_SCRAWL, self::ACTION_TYPE_UPLOAD_VIDEO, self::ACTION_TYPE_UPLOAD_FILE => new UploadAction($get_data),
            self::ACTION_TYPE_WX_CRAWLER => new WxCrawlerAction($get_data),
            self::ACTION_TYPE_LIST_FILE, self::ACTION_TYPE_LIST_IMAGE => new ListAction($get_data),
            self::ACTION_TYPE_CATCH_IMAGE => new ImageCrawlerAction($get_data, $post_data),
            default => new DefaultAction($get_data),
        };
    }

}