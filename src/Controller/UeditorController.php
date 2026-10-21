<?php

namespace FormItem\Ueditor\Controller;


use FormItem\Ueditor\Lib\Action\Context;
use FormItem\Ueditor\Lib\UeditorAuth;

class UeditorController extends \Think\Controller{

    public function __construct(){
        parent::__construct();
    }

    private function _isCallback(array $get_data):bool{
        return isset($get_data["callback"]);
    }

    public function index():void{
        // UEditor handles all of its operations through this endpoint, so require
        // the same administrator session used by the rest of the admin area.
        if (!UeditorAuth::canUse()) {
            $this->handleUnauthorized();
            return;
        }

        $get_data = I("get.");
        $action_type = $get_data["action"];

        $result = Context::genActionByType($action_type, $get_data)?->run();

        if ($this->_isCallback($get_data)){
           $this->handleCallback($result, $get_data);
        }

        echo $result;
    }

    protected function handleUnauthorized(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'state' => '未登录或登录已过期'
        ), JSON_UNESCAPED_UNICODE);
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
