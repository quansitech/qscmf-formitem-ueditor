<?php
// IDE 辅助文件 - 为 ColumnsContainer 的 ueditor 魔术方法提供类型提示
// 此文件仅供 IDE 分析使用，不会被执行

namespace AntdAdmin\Component\Form;

use FormItem\Ueditor\Column\Ueditor;

/**
 * IDE 类型提示辅助
 *
 * 当使用 ColumnsContainer 的魔术方法调用 ueditor 时，
 * IDE 可以通过此文件识别返回类型
 */
class ColumnsContainer {
    /**
     * 创建 Ueditor 列类型
     *
     * @param string $dataIndex 数据索引字段名
     * @param string $title 列标题
     * @return Ueditor 返回 Ueditor 列类型实例
     *
     * @example
     * $container->ueditor('content', '内容')
     *     ->setConfig(['width' => 800])
     *     ->setExtraScripts(['custom.js']);
     */
    public function ueditor(string $dataIndex, string $title): Ueditor {}
}