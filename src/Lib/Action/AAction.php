<?php

namespace FormItem\Ueditor\Lib\Action;

abstract class AAction
{

    protected array $get_data;

    public function __construct(array $get_data){
        $this->get_data = $get_data;
    }


    abstract public function run():string;

}