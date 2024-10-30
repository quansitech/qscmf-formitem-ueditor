<?php

namespace FormItem\Ueditor\Controller;


use FormItem\Ueditor\Lib\Action\Context;

class UeditorController extends \Think\Controller{

    public function __construct(){
        parent::__construct();
    }

    private function _isCallback(array $get_data):bool{
        return isset($get_data["callback"]);
    }

    public function index():void{
        $get_data = I("get.");
        $action_type = $get_data["action"];

        $result = Context::genActionByType($action_type, $get_data)?->run();

        if ($this->_isCallback($get_data)){
           $this->handleCallback($result, $get_data);
        }

        echo $result;
    }

    protected function handleCallback($result, array $get_data): void
    {
        if (preg_match("/^[\w_]+$/", $get_data["callback"])) {
            echo htmlspecialchars($get_data["callback"]) . '(' . $result . ')';
        }

        echo json_encode(array(
            'state'=> 'callback参数不合法'
        ));

    }

}
