# 升级指南

#### 微信文章抓取安全更新

升级前请检查项目 `.env` 中的微信抓取配置：

1. 未配置 `UEDITOR_WX_CRAWLER_ALLOWED_HOSTS` 时，默认仅允许 `mp.weixin.qq.com` 和 `weixin.qq.com`。自定义配置会替换默认白名单，请同时保留需要使用的微信域名。
2. 使用 `UEDITOR_WX_CRAWLER_PREFIX` 的项目，必须将代理域名加入白名单，否则请求会返回 HTTP 400。例如：

   ```dotenv
   UEDITOR_WX_CRAWLER_PREFIX=https://wx-proxy.example.com/?url=
   UEDITOR_WX_CRAWLER_ALLOWED_HOSTS=mp.weixin.qq.com,weixin.qq.com,wx-proxy.example.com
   ```

   原始链接和最终请求地址均校验 HTTP(S) 协议及域名白名单。仅最终请求地址进行 DNS 解析和公网 IP 校验，原始微信域名不需要在当前服务器上能够解析。内网、回环和其他非公网地址仍禁止访问；使用内网代理的项目需先调整为可通过公网访问的可信代理。
3. HTTPS 证书校验默认开启。代理使用自签证书时，应先在服务器配置受信任的 CA；`UEDITOR_WX_CRAWLER_VERIFY_TLS=false` 可显式关闭校验，但会失去证书验证保护。

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
