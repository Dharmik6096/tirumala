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
            'TblMilkCollection' => [
                'ext' => '.csv',
                'param' => 'union_code,mcc_plant_code,bmc_code,from_date,to_date',
                'sp_name' => 'rpt_MIS_SDSAPReport',
                'export_title' => 'mcc_plant_code+_+SD+_+applicable_date:date+_+shift_code',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath . '/../')) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => 'SD/Inbound'
            ],
            'TblBmcCollection' => [
                'ext' => '.csv',
                'param' => 'union_code,mcc_plant_code,bmc_code,from_date,to_date',
                'sp_name' => 'rpt_MIS_WQSAPReport',
                'sp_check_data' => 'sp_checkDatacompleteness_BMC',
                'param2' => 'union_code,mcc_plant_code,shift_date:date:shift_code',
                'export_title' => 'mcc_plant_code+_+WQ+_+applicable_date:date+_+shift_code',
                'summary_model' => 'TblShiftSummary',
                'file_path' => str_replace('\\', '/', realpath(\Yii::$app->basePath . '/../')) . \Yii::$app->params['FTPDirPath'] . 'upload/',
                'ftp_path' => 'WQ/Inbound'
            ],
        ];
    }

}
