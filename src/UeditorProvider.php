<?php
namespace FormItem\Ueditor;

use Bootstrap\Provider;
use Bootstrap\RegisterContainer;
use FormItem\Ueditor\FormType\Ueditor\Ueditor;

class UeditorProvider implements Provider{

    public function register(){
        RegisterContainer::registerFormItem('ueditor', Ueditor::class);

        RegisterContainer::registerSymLink(WWW_DIR . '/Public/ueditor', __DIR__ . '/../asset/ueditor');
    }
}