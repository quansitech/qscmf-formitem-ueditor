<?php

foreach (array(__DIR__ . '/../../../../../../../vendor/autoload.php', __DIR__ .
    '/../../../../../../vendor/autoload.php') as $file) {
    if (file_exists($file)) {
        define('VENDOR_DIR', dirname($file));

        break;
    }
}
require_once VENDOR_DIR . '/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(VENDOR_DIR . '/..');
$dotenv->load();

function osUpload($type, $file_urls, $upload_config, $upload_type){
    $common_http_config = include VENDOR_DIR . '/../app/Common/Conf/Config/http_config.php';
    $common_upload_config = include VENDOR_DIR . '/../app/Common/Conf/Config/upload_config.php';
    $common_config = array_merge((array)$common_http_config, (array)$common_upload_config);

    if (class_exists('\FormItem\ObjectStorage\Lib\Vendor\Context')) {
        return existsOsPackage($type, $common_config, $file_urls, $upload_config, $upload_type);
    }else{
        throw new \Think\Exception("please install quansitech/qscmf-formitem-object-storage");
    }
}

function combineOsFileUrl($vendor_cls, $objet):string{
    $host_key = $vendor_cls->getVendorConfig()->getHostKey();
    $host = $vendor_cls->getUploadConfig()->getAll()[$host_key];
    // oss_public_host
    return $host.'/'.$objet;
}

function getHeaderOptions($vendor_cls):array{
    return $vendor_cls->getUploadConfig()->getMeta();
}

function existsOsPackage($type, $common_config, $file_urls, $upload_config, $upload_type){
    $upload_type_config = $common_config['UPLOAD_TYPE_' . strtoupper($type)];
    $vendor_type = $_GET['vendor_type'];

    $vendor_type = \FormItem\ObjectStorage\Lib\Common::getVendorType($type,$vendor_type,$upload_type_config);

    $os_client = \FormItem\ObjectStorage\Lib\Vendor\Context::genVendorByType($vendor_type);
    $os_client->setUploadConfig($type, $upload_type_config);

    $new_info_list = [];

    foreach ($file_urls as $one_file) {
        $item = new Uploader($one_file, $upload_config, $upload_type);
        $info = $item->getFileInfo();
        if($info['state'] != 'SUCCESS'){
            $new_info_list[] = $info;
            continue;
        }
        $file = realpath(VENDOR_DIR . '/../www' . $info['url']);
        $r = $os_client->genClient($type, false)->uploadFile($file, trim($info['url'], '/'), getHeaderOptions($os_client));
        unlink($file);
        $info['url'] = parseUrl(combineOsFileUrl($os_client, $r) , 0, $_GET['url_prefix'], $_GET['url_suffix']);

        $new_info_list[] = [
            "state" => $info["state"],
            "url" => $info["url"],
            "size" => $info["size"],
            "title" => htmlspecialchars($info["title"]),
            "original" => htmlspecialchars($info["original"]),
            "source" => $one_file
        ];
    }

    return $new_info_list;
}

