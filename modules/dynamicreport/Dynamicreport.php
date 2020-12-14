<?php

namespace app\modules\dynamicreport;

use Yii;
use app\modules\dynamicreport\models\TblReportList;
use app\modules\dynamicreport\models\TblReportControlMapping;
use app\modules\dynamicreport\models\TblControlList;

/**
 * dynamicreport module definition class
 */
class Dynamicreport extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\dynamicreport\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        // custom initialization code goes here
    }

    public static function getLabels($l) {
        $data = [];
        $report_model = new TblReportList();
        $report_model->report_code = $l;
        $report_model = $report_model->getData();
        if (!empty($report_model)) {
            $control = TblControlList::find()
                    ->join('inner join', 'tbl_report_control_mapping', 'tbl_control_list.control_code=tbl_report_control_mapping.control_code')
                    ->where(['tbl_report_control_mapping.report_code' => $report_model->report_code, 'tbl_report_control_mapping.is_active' => 1])
                    ->all();
            $data['controls'] = $control;
            $data['title']=$report_model->report_name;
            $data['sp_name']=$report_model->sp_name;
            $data['sp_param']=$report_model->sp_param;
            $data['report_rule']=json_decode($report_model->report_rule);
        }
        return $data;
    }

}
