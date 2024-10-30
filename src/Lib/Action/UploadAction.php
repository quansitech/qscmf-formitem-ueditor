<?php

namespace FormItem\Ueditor\Lib\Action;

use FormItem\Ueditor\Lib\Helper;
use FormItem\Ueditor\Lib\OSUpload;
use FormItem\Ueditor\Lib\UeditorConfig;
use FormItem\Ueditor\Lib\Uploader;

class UploadAction extends AAction
{

    protected string $type;
    protected array $config;
    
    public function __construct(array $get_data){
        parent::__construct($get_data);

        $this->type = $get_data['action'];
        $this->config = UeditorConfig::build()->getAll();
    }
    
    protected function initConfig():array{
        $base64 = "upload";

        /* 上传配置 */
        switch (htmlspecialchars($this->type)) {
            case Context::ACTION_TYPE_UPLOAD_IMAGE:
                $config = array(
                    "pathFormat" => $this->config['imagePathFormat'],
                    "maxSize" => $this->config['imageMaxSize'],
                    "allowFiles" => $this->config['imageAllowFiles']
                );
                $fieldName = $this->config['imageFieldName'];
                break;
            case Context::ACTION_TYPE_UPLOAD_SCRAWL:
                $config = array(
                    "pathFormat" => $this->config['scrawlPathFormat'],
                    "maxSize" => $this->config['scrawlMaxSize'],
                    "allowFiles" => $this->config['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                );
                $fieldName = $this->config['scrawlFieldName'];
                $base64 = "base64";
                break;
            case Context::ACTION_TYPE_UPLOAD_VIDEO:
                $config = array(
                    "pathFormat" => $this->config['videoPathFormat'],
                    "maxSize" => $this->config['videoMaxSize'],
                    "allowFiles" => $this->config['videoAllowFiles']
                );
                $fieldName = $this->config['videoFieldName'];
                break;
            case Context::ACTION_TYPE_UPLOAD_FILE:
            default:
                $config = array(
                    "pathFormat" => $this->config['filePathFormat'],
                    "maxSize" => $this->config['fileMaxSize'],
                    "allowFiles" => $this->config['fileAllowFiles']
                );
                $fieldName = $this->config['fileFieldName'];
                break;
        }
        
        return [$base64, $config, $fieldName];
    }

    /**
     * 得到上传文件所对应的各个参数,数组结构
     * array(
     *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
     *     "url" => "",            //返回的地址
     *     "title" => "",          //新文件名
     *     "original" => "",       //原始文件名
     *     "type" => ""            //文件类型
     *     "size" => "",           //文件大小
     * )
     */
    /* 生成上传实例对象并完成上传 */
    
    public function run():string
    {
      
        [$base64, $config, $fieldName] = $this->initConfig();

        if($this->get_data['os']){
            $type = $this->get_data['type'];
            if(!$type){
                $type = 'image';
            }
            $upload_res_list = OSUpload::build($this->get_data)->osUpload($type, [$fieldName], $config, $base64);
            return json_encode($upload_res_list[0]);
        }

        $up = new Uploader($fieldName, $config, $base64);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */

        $file_info = $up->getFileInfo();

        $file_info['url'] = Helper::parseUrl($file_info['url'], $this->get_data['urldomain'], $this->get_data['url_prefix'], $this->get_data['url_suffix']);

        return json_encode($file_info);
    }

}