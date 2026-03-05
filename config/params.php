<?php

$eipl_params = [
    'adminEmail' => 'admin@example.com',
//    'eiplDirPath' => 'C:/ftp/eipl/',
    'eiplDirPath' => 'ftp/eipl/',
    'unionBankDirPath' => 'C:/unionbank/',
    'biplDirPath' => '/LOCALBIPL/',
    'rateFilesPath' => 'ratechart',
    'biplRateUtilityPath' => 'utility/BIPL/Rate',
    'biplMemberUtilityPath' => 'utility/BIPL/Vendor',
    'biplCollectionUtilityPath' => 'utility/BIPL/Collection',
    'jasper_server' => 'http://localhost:13099/jasperserver',
    'jasper_username' => 'jasperadmin',
    'jasper_password' => 'jasperadmin',
    'report_path' => '/Reports/',
    'dpu_docs_path' => 'uploads/dpu-docs/',
    'complaint_dir_path' => 'uploads/complaint-docs/',
    'collection_dir_path' => '/web/collection-data/',
    'logo_path' => '/themes/emilk/assets/images/union_logo/',
    'temp_logo_path' => '/import/image/',
    'crystal_report_path' => '\modules\crystalreports\reports',
    'notification_url' => 'https://fcm.googleapis.com/fcm/send',
    'everest_notification_key' => 'AAAA2-vGiKw:APA91bH6ktJ0IRHSyIYqToe5OoUh5IJp2w5yVoNvqGc3AZtTjgTdRyDa9fbcLkyp6PCVfh03S9-2Si7e6ofwnZ_vv0OCPD4uTsKAxPci6mA-06630U5ydXMRqca0eFEcMz4CCw0bwlyi',
    'bmc_notification_key' => 'AAAAwaXvLGk:APA91bFt_cBNfXsVl6L6U4RiZrlotbQQyxvKBmsxGnxHUI8LI23FMf4hEntk52_1Gwxi7z9rC22e-3j89tzYz47v8lrw8aVbeCSU0bej719usgK8JgB3LJw81BS87IrquCEpQ76cELAS',
    'member_notification_key' => 'AAAAbdLVu64:APA91bEdjAOomODXEbjtGr74P5-NQDpN3FnlVPg9tzyf8SChLTkUDz1wtv8tHYj0OjeU1DZhpq0BBD81twAjbZlAVJNJ3ogpIYsQCJZ1cN1Xr9fgfZD6ASdHirmOIatOfqRH52u0AyBf',
    'projectPath' => 'E:\Apache24\htdocs\tirumala',
    'convert_crystal_report_path' => 'utility\CrystalReportsNinja-master\Deployment',
    'rptHtmlPath' => '\modules\crystalreports\html',
    'ho_notification_key' => 'AAAATsR3lJw:APA91bEDDapzfO1L7c-7MAV3lxLElNHpf2rHCZrj2dDOUF4io3zCzi1gUS5DcusK3SqPYMecucn7t2YXOP_k5_0eGfSgj34fhxz0T1vsGpXAc30lOXVK_l5d-EW28gA_EIF-rtn3jSBB',
    'vendorApiErrorLogPath' => 'VendorAPI',
    'namaste_collection_url' => 'https://rspoappprd.hec.rsplgroup.com:50001/RESTAdapter/',
    //'namaste_api_header' => ['userid' => 'PO_EVEREST', 'password' => 'Rspl$1234'],
    'namaste_api_header' => ['Authorization' => 'Basic UE9fRVZFUkVTVDpyc3BsQDMyMQ=='],
    'pds_path' => '/PDS/',
    'eipl_code' => 'NIFPL',
    'client_url' => 'http://clients.everestinstruments.com/clients/',
    'soap_api_url' => 'http://103.210.72.119/AMCUService/Service.asmx?WSDL',
    'FTPDirPath' => '/SAPFILES/',
    //'google_map_api_key' => 'AIzaSyD78APuRJaxdjFBBSJjKag_CD2tWXSrjhE',
    'google_map_api_key' => 'AIzaSyAKI  qWEqIqqF-IZt8_7WqRjxIUUDR0yKjc',
    'data_exchange_vendor_code' => 'EIPLMDPL',
    'attachment_server' => 'localhost',
    'data_exchange_un' => 'umang-admin',
    'data_exchange_pw' => 'P@1234',
    'bsVersion' => '5.x',
    'document_upload' => 'document_upload/',
    'banner_upload' => '/uploads/banner_upload/',
    'import_path' => 'import/',
    'feedback_upload' => '/uploads/feedback_upload/',
    'sap_data_files' => '/sap_data_files/',
    'payment_xml_upload' => '/uploads/payment_xml_upload/',
    'software_complaint_dir_path' => '/uploads/software-complaint-docs/',
    'data_exchange_url' => 'http://www.w3.org/2003/05/soap-envelope',
    'FTPVendorDirPath' => '/web/sap_file_upload/',
];
/* Application Server wise custom param file changes - asmita - 15/06/2023 */

$custom_file = __DIR__ . '/env.php'; // returns an array same formate as params.php
$custom_params = file_exists($custom_file) ? require($custom_file) : [];
$all_params = array_merge($eipl_params, $custom_params);

/* Application Server wise custom param file changes - asmita - 15/06/2023 */

return $all_params;
