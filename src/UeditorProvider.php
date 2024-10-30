<?php
namespace FormItem\Ueditor;

use Bootstrap\Provider;
use Bootstrap\RegisterContainer;
use FormItem\Ueditor\Behaviors\HandleOsResBehavior;
use FormItem\Ueditor\Behaviors\InjectOsParamBehavior;
use FormItem\Ueditor\Controller\UeditorController;
use FormItem\Ueditor\FormType\Ueditor\Ueditor;
use Think\Hook;

class UeditorProvider implements Provider{

    public function register(){
        $this->addHooks();

        RegisterContainer::registerController('extends', 'Ueditor'
            , UeditorController::class);

        RegisterContainer::registerFormItem('ueditor', Ueditor::class);

        RegisterContainer::registerSymLink(WWW_DIR . '/Public/ueditor'
            , __DIR__ . '/../asset/ueditor');
    }

    protected function addHooks():void{
        Hook::add('handle_os_callback', HandleOsResBehavior::class);
//        Hook::add('inject_os_params', InjectOsParamBehavior::class);
    }

}