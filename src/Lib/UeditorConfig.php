<?php

namespace FormItem\Ueditor\Lib;

class UeditorConfig
{

    private static ?UeditorConfig $_instance = null;

    protected array $ueditor_config = [];

    public static function build():self{
        if (is_null(self::$_instance)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct(){
        $this->ueditor_config = $this->init();
    }

    protected function init():array{
        if(file_exists(APP_DIR . '/Common/Conf/ueditor_config.json')){
            $config_file = APP_DIR . '/Common/Conf/ueditor_config.json';
        }
        elseif(file_exists(APP_DIR . '/Common/Conf/ueditor_config.php')){
            $config_file = APP_DIR . '/Common/Conf/ueditor_config.php';
        }
        else{
            $config_file =__DIR__."/config.json";
        }

        $extend = pathinfo($config_file, PATHINFO_EXTENSION);

        if ($extend === 'php'){
            $config = include $config_file;
        }else{
            $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($config_file)), true);
        }

        return $config;
    }

    public function getAll():array{
        return $this->ueditor_config;
    }

    public function __call($method,$args) {
        if (function_exists($this->$method)){
            $this->$method($args);
        }

        if(str_starts_with($method, 'get')) {
            $key = lcfirst(substr($method, 3));
            return $this->ueditor_config[$key];
        }
    }


}