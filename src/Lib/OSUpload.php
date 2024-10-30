<?php

namespace FormItem\Ueditor\Lib;

class OSUpload
{

    private static ?OSUpload $_instance = null;
    protected array $get_data;


    public function __construct(array $get_data){
        $this->get_data = $get_data;
    }

    public static function build(array $get_data):self{
        if (is_null(self::$_instance)){
            self::$_instance = new self($get_data);
        }

        return self::$_instance;
    }

    public function osUpload($type, $file_urls, $upload_config, $upload_type): array
    {
        $key = 'UPLOAD_TYPE_' . strtoupper($type);
        if(C($key, null, '') == '') {
            E("没有配置" . $key . "类型的上传配置");
        }
        $upload_type_config = C($key);

        if (class_exists('\FormItem\ObjectStorage\Lib\Vendor\Context')) {
            $web_upload_switch = $this->canWebUpload($upload_type_config);

            return $web_upload_switch ? $this->policyGet($type, $upload_type_config, $file_urls)
                : $this->existsOsPackage($type, $upload_type_config, $file_urls, $upload_config, $upload_type);
        }

        throw new \Think\Exception("please install quansitech/qscmf-formitem-object-storage");
    }

    protected function combineOsFileUrl($vendor_cls, $objet):string{
        $host_key = $vendor_cls->getVendorConfig()->getHostKey();
        $host = $vendor_cls->getUploadConfig()->getAll()[$host_key];
        // oss_public_host
        return $host.'/'.$objet;
    }

    protected function getHeaderOptions($vendor_cls):?array{
        return $vendor_cls->getUploadConfig()->getMeta();
    }

    protected function existsOsPackage($type, $upload_type_config, $file_urls, $upload_config, $upload_type): array
    {
        $vendor_type = $this->get_data['vendor_type'];

        $vendor_type = \FormItem\ObjectStorage\Lib\Common::getVendorType($type,$vendor_type,$upload_type_config);

        $os_client = \FormItem\ObjectStorage\Lib\Vendor\Context::genVendorByType($vendor_type);
        $os_client->setUploadConfig($type, $upload_type_config);

        $new_info_list = [];

        foreach ($file_urls as $one_file) {
            $item = new Uploader($one_file, $upload_config, $upload_type);
            $info = $item->getFileInfo();
            if($info['state'] !== 'SUCCESS'){
                $new_info_list[] = $info;
                continue;
            }
            $file = realpath(APP_DIR . '/../www' . $info['url']);
            $r = $os_client
                ->genClient($type, false)
                ->uploadFile($file, trim($info['url'], '/'), $this->getHeaderOptions($os_client));
            unlink($file);
            $info['url'] = Helper::parseUrl($this->combineOsFileUrl($os_client, $r) , 0
                , $this->get_data['url_prefix'], $this->get_data['url_suffix']);

            $catch_res = [];
            Helper::parseCatchRes($one_file, $info, $catch_res);
            $new_info_list[] = $catch_res;
        }

        return $new_info_list;
    }

    protected function genOsClient($type, $upload_type_config): array
    {
        $vendor_type = $this->get_data['vendor_type'];

        $vendor_type = \FormItem\ObjectStorage\Lib\Common::getVendorType($type,$vendor_type,$upload_type_config);

        $os_client = \FormItem\ObjectStorage\Lib\Vendor\Context::genVendorByType($vendor_type);
        $os_client && $os_client->setUploadConfig($type, $upload_type_config);

        return [$os_client, $vendor_type, $upload_type_config];
    }

    protected function policyGet($type, $upload_type_config, $file_urls){
        [$os_client, $vendor_type, $upload_type_config] = $this->genOsClient($type, $upload_type_config);

        $new_info_list = [];

        foreach ($file_urls as $one_file) {
            $response = $os_client->policyGet($type);
            $response['vendor_type'] = $vendor_type;
            $response['statue'] = 'UPLOAD';

            $new_info_list[] = $response;
        }

        return $new_info_list;
    }

    protected function canWebUpload($upload_type_config, $action_name = ''):bool
    {
        if (isset($this->get_data['web_upload'])){
            return $this->get_data['web_upload'] === '1';
        }

        return false;
    }

}