<?php

namespace FormItem\Ueditor\Behaviors;

class HandleOsResBehavior
{
    public function run(&$params)
    {
        $file_data = $params['file_data'];
        $custom_param =  $params['param'];
        if ($custom_param['scence'] === 'ueditor'){
            $data = [
                "state" => 'SUCCESS',
                "url" => $file_data["url"],
                "size" => $file_data["size"],
                "title" => htmlspecialchars($file_data["title"]),
                "original" => htmlspecialchars($file_data["original"]),
                "source" => $file_data
            ];

            $params['file_data'] = $data;
            $params['res'] = true;
        }

    }

}