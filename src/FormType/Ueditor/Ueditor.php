<?php
namespace FormItem\Ueditor\FormType\Ueditor;

use AntdAdmin\Component\ColumnType\BaseColumn;
use Illuminate\Support\Str;
use Qscmf\Builder\Antd\BuilderAdapter\FormAdapter\IAntdFormItem;
use Qscmf\Builder\FormType\FormType;
use Think\View;

class Ueditor implements FormType, IAntdFormItem
{

    public function build(array $form_type){
        $view = new View();
        $view->assign('form', $form_type);
        $view->assign('gid', Str::uuid());
        if(C('CUSTOM_UEDITOR_JS_CONFIG')){
            $view->assign('configJs', C('CUSTOM_UEDITOR_JS_CONFIG'));
            $view->assign('home_url', __ROOT__ . '/Public/ueditor/');
        }
        else{
            $view->assign('configJs', __ROOT__ . '/Public/ueditor/ueditor.config.js');
        }
        $content = $view->fetch(__DIR__ . '/ueditor.html');
        
        return $content;
    }

    public function formAntdRender($options): BaseColumn
    {
        $column = new \AntdAdmin\Component\Form\ColumnType\Ueditor($options['name'], $options['title']);

        return $column;
    }
}