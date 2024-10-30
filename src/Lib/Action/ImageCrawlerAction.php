<?php

namespace FormItem\Ueditor\Lib\Action;

use FormItem\Ueditor\Lib\Helper;
use FormItem\Ueditor\Lib\OSUpload;
use FormItem\Ueditor\Lib\UeditorConfig;
use FormItem\Ueditor\Lib\Uploader;

class ImageCrawlerAction extends AAction
{

    protected array $config;
    protected array $post_data = [];

    public function __construct(array $get_data, array $post_data = []){
        parent::__construct($get_data);

        $this->config = UeditorConfig::build()->getAll();
        $this->post_data = $post_data;
    }

    protected function initConfig():array{
        $config = array(
            "pathFormat" => $this->config['catcherPathFormat'],
            "maxSize" => $this->config['catcherMaxSize'],
            "allowFiles" => $this->config['catcherAllowFiles'],
            "oriName" => "remote.png"
        );
        $fieldName = $this->config['catcherFieldName'];

        return [$config, $fieldName];
    }

    public function run(): string
    {
        [$config, $fieldName] = $this->initConfig();

        /* 抓取远程图片 */
        $list = array();
        if (isset($this->post_data[$fieldName])) {
            $source = $this->post_data[$fieldName];
        } else {
            $source = $this->get_data[$fieldName];
        }

        if($this->get_data['os']){
            $type = $this->get_data['type'];
            if(!$type){
                $type = 'image';
            }
            $source = is_array($source) ? $source : array($source);
            $upload_res_list = OSUpload::build($this->get_data)->osUpload($type,$source, $config, "remote");
            $list = array_merge($list, $upload_res_list);

            /* 返回抓取数据 */
            return json_encode(array(
                'state'=> count($list) ? 'SUCCESS':'ERROR',
                'list'=> $list
            ));
        }

        foreach ($source as $imgUrl) {
            $item = new Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            $info['url'] = Helper::parseUrl($info['url'], $this->get_data['urldomain'], $this->get_data['url_prefix'], $this->get_data['url_suffix']);

            $catch_res = [];
            Helper::parseCatchRes($imgUrl, $info, $catch_res);
            $list[] = $catch_res;
        }

        /* 返回抓取数据 */
        return json_encode(array(
            'state'=> count($list) ? 'SUCCESS':'ERROR',
            'list'=> $list
        ));

    }

}