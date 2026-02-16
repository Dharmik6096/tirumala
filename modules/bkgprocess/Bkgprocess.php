<?php

namespace app\modules\bkgprocess;

/**
 * bkgprocess module definition class
 */
class Bkgprocess extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\bkgprocess\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        // custom initialization code goes here
    }

    public static function FTPProcess() {
        return [
            /* 'TblMilkCollection' => ['ext' => '.xlsx',
              'param' => 'union_code,mcc_code:union_code,date:string:shift',
              'sp_name' => 'rpt_MIS_WQSAPReport',
              'sp_name2' => 'sp_checkDatacompleteness_BMC',
              'param2' => 'union_code,mcc_code,date:string:shift',
              'export_title' => true,
              'module_name' => 'TblMilkCollection'], */
            'TblMilkCollection' => [
                'ext' => '.csv',
                'param' => 'union_code,mcc_plant_code,bmc_code,from_date,to_date',
                'sp_name' => 'rpt_MIS_SDSAPReport',
                'export_title' => 'module_code+_+SD+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => 'SD/Inbound'
            ],
            'TblMilkCollection_Ananda' => [
                'ext' => '.csv',
                'export_title' => 'module_code+_+SD+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => '/inbox',
                'skip_header' => TRUE,
                'append_ftp_path' => TRUE,
            ],
            'TblBmcCollection' => [
                'ext' => '.csv',
                'param' => 'union_code,mcc_plant_code,bmc_code,from_date,to_date',
                'sp_name' => 'rpt_MIS_WQSAPReport',
                'export_title' => 'mcc_plant_code+_+WQ+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => 'WQ/Inbound'
            ],
            'TblBmcCollection_collection' => [
                'ext' => '.csv',
                'param' => 'union_code,mcc_plant_code,bmc_code,from_date,to_date',
                'sp_name' => 'rpt_MIS_WQSAPReport_collection',
                'export_title' => 'mcc_plant_code+_+WQ+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => 'WQ/Inbound'
            ],
            'TblBmcCollection_dispatch' => [
                'ext' => '.csv',
                'param' => 'union_code,mcc_plant_code,bmc_code,from_date,to_date',
                'sp_name' => 'rpt_MIS_WQSAPReport_dispatch',
                'export_title' => 'mcc_plant_code+_+WQ+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => 'WQ/Inbound'
            ],
//            'TblBmcCollection' => [
//                'ext' => '.csv',
//                'param' => 'union_code,mcc_plant_code,applicable_date',
//                'sp_name' => 'rpt_MIS_WQSAPReport',
//                'sp_check_data' => 'sp_checkDatacompleteness_BMC',
//                'param2' => 'union_code,mcc_plant_code,shift_date:date:shift_code',
//                'export_title' => 'mcc_plant_code+_+WQ+_+applicable_date:date+_+shift_code',
//                'summary_model' => 'TblShiftSummary',
//                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath )) . \Yii::$app->params['FTPDirPath'] . 'upload/',
//                'ftp_path' => 'WQ/Inbound'
//            ],
            'TblBmcCollection_dodla_VM' => [
                'ext' => '.xlsx',
                'param' => 'union_code,mcc_plant_code,bmc_code,from_date',
                'sp_name' => 'mis_bmc_collection_vm',
                'export_title' => 'mcc_plant_code+_+WQ+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
            ],
            'TblBmcCollection_dodla_WQ' => [
                'ext' => '.xlsx',
                'param' => 'union_code,mcc_plant_code,bmc_code,from_date',
                'sp_name' => 'mis_bmc_collection_wq',
                'export_title' => 'mcc_plant_code+_+WQ+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
            ],
            'TblMilkCollection_cdpl_VM' => [
                'ext' => '.csv',
                'param' => 'union_code,mcc_plant_code,bmc_code,dcs_code,from_date,to_date',
                'sp_name' => 'mis_vmcc_collection_date_wise',
                'export_title' => 'dcs_code+_mcc_plant_code+_+WQ+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => '/CDPL/CDPL_MCC/VMCC/INBOX/' //'/CDPL/MCC/VMCC/inbox/'
            ],
            'TblSapDataDaily' => [
                'ext' => '.TXT',
                'implode_char' => '|',
                'param' => 'union_code,module_code,applicable_date,shift_code',
                'sp_name' => 'sp_file_sap_data_daily_umang',
                'export_title' => '',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'UMANG/DAILY/',
                'ftp_path' => '/Daily/',
                'append_ftp_path' => TRUE,
                'skip_header' => TRUE,
            ],
            'TblVspPayment' => [
                'ext' => '.TXT',
                'implode_char' => '|',
                'param' => 'union_code,module_code,from_datetime,to_datetime',
                'sp_name' => 'sp_file_sap_data_weekly_umang',
                'export_title' => '',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'UMANG/WEEKLY/',
                'ftp_path' => '/Weekly/',
                'append_ftp_path' => TRUE,
                'skip_header' => TRUE,
            ],
            'TblBmcCollection_Ananda' => [
                'ext' => '.xml',
                'param' => 'union_code,mcc_plant_code,from_date',
                'sp_name' => 'rpt_MIS_WQSAPReport_Ananda',
                'export_title' => 'module_code+_+SD+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => '/Gopaljee/SAPPOPRD/Inbound/',
                'append_ftp_path' => TRUE,
                'append_ftp_collection_code' => 'rmrd',
            ],
            'TblMemberProvisional' => [
                'ext' => '.xml',
                'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,dcs_code,from_date,to_date,sap_status',
                'sp_name' => 'member_provisional_sap_ftp_data_upload_mpcrmrd',
                'export_title' => '',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath)) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => '/PRD/ZPIB',
                'append_ftp_path' => TRUE,
                'xml_tag' => 'MAF_MT_Sen,Header,item'
            ],
        ];
    }

}
