# 升级指南

#### v1 -> v2 升级步骤
   + 使用云存储服务
     + 依赖的扩展包 *quansitech/qscmf-formitem-object-storage* 版本为 v2.3.0 及以上
         + 修改服务器统一请求接口路径
           ```label
           旧 /public/ueditor/php/controller.php

           新 /extends/ueditor/index?os=1&type=editor&vendor_type=xxx
           ```
           
   + 只使用本地存储
     + 修改服务器统一请求接口路径
       ```label
       旧 /public/ueditor/php/controller.php

       新 /extends/ueditor/index
       ```