<?php

namespace FormItem\Ueditor\Lib\Action;

use FormItem\Ueditor\Lib\UeditorConfig;

class ListAction extends AAction
{

    protected string $type;
    protected array $config;
    
    public function __construct(array $get_data){
        parent::__construct($get_data);

        $this->type = $get_data['action'];
        $this->config = UeditorConfig::build()->getAll();
    }

    protected function initConfig():array{
        /* 判断类型 */
        switch ($this->get_data['action']) {
            /* 列出文件 */
            case Context::ACTION_TYPE_LIST_FILE:
                $allowFiles = $this->config['fileManagerAllowFiles'];
                $listSize = $this->config['fileManagerListSize'];
                $path = $this->config['fileManagerListPath'];
                break;
            /* 列出图片 */
            case Context::ACTION_TYPE_LIST_IMAGE:
            default:
                $allowFiles = $this->config['imageManagerAllowFiles'];
                $listSize = $this->config['imageManagerListSize'];
                $path = $this->config['imageManagerListPath'];
        }
        
        $allowFiles = substr(str_replace(".", "|", implode("", $allowFiles)), 1);

        return [$allowFiles, $listSize, $path];
    }


    public function run(): string
    {
        [$allowFiles, $listSize, $path] = $this->initConfig();
        
        /* 获取参数 */
        $size = isset($this->get_data['size']) ? htmlspecialchars($this->get_data['size']) : $listSize;
        $start = isset($this->get_data['start']) ? htmlspecialchars($this->get_data['start']) : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = $_SERVER['DOCUMENT_ROOT'] . (str_starts_with($path, "/") ? "":"/") . $path;
        $files = $this->getFiles($path, $allowFiles);
        if (is_null($files) || !count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => 0
            ));
        }
        
        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }
        //倒序
        //for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
        //    $list[] = $files[$i];
        //}

        /* 返回数据 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;
    }


    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    protected function getFiles($path, $allowFiles, array &$files = array()): ?array
    {
        if (!is_dir($path)) return null;
        if(!str_ends_with($path, '/')) $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file !== '.' && $file !== '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getFiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                        $files[] = array(
                            'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }
}