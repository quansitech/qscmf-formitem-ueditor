<?php


namespace FormItem\Ueditor\Lib\Action;

use FormItem\Ueditor\Lib\UeditorConfig;

class ConfigAction extends AAction
{

    public function run(): string
    {
        return json_encode(UeditorConfig::build()->getAll());
    }

}