<?php

namespace FormItem\Ueditor\Lib\Action;

class DefaultAction extends AAction
{

    public function run(): string
    {
        return  json_encode(array(
            'state'=> '请求地址出错'
        ));

    }

}