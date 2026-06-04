<?php

namespace FormItem\Ueditor\Column;

use AntdAdmin\Component\ColumnType\BaseColumn;

class Ueditor extends BaseColumn
{
    protected string $ueditorPath = '';

    public function __construct($dataIndex, $title)
    {
        parent::__construct($dataIndex, $title);
        $this->ueditorPath = asset('ueditor');
        $this->render_data['fieldProps'] = [
            'config' => [
                'serverUrl' => U('/extends/ueditor/index'),
            ]
        ];
    }

    public function setUeditorPath(string $path)
    {
        $this->ueditorPath = $path;
        return $this;
    }

    public function render()
    {
        $this->render_data['fieldProps']['ueditorPath'] = $this->getUeditorPath();

        return parent::render();
    }

    /**
     * @return string
     */
    public function getUeditorPath()
    {
        return $this->ueditorPath;
    }

    protected function getValueType(): string
    {
        return 'ueditor';
    }

    public function setConfig($config)
    {
        $this->render_data['fieldProps']['config'] = $config;
        return $this;
    }

    /**
     * 设置额外脚本路径
     * @param string[] $extraScripts
     * @return $this
     */
    public function setExtraScripts(array $extraScripts)
    {
        $this->render_data['fieldProps']['extraScripts'] = $extraScripts;
        return $this;
    }

    protected function beforeAddForm()
    {
        parent::beforeAddForm();
        $this->setFormItemWidth(18);
    }

}