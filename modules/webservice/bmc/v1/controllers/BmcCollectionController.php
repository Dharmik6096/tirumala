<?php

namespace app\modules\webservice\bmc\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\collection\models\TblBmcCollection;
use Yii;

class BmcCollectionController extends ChildController {

    public function actionCollectionData() {
        $model = new TblBmcCollection();
        $model->setAttributes($this->post_data);
        $bmc_code = $this->post_data['bmc_code'];
        $content = $this->post_data['content'];
        $from_date = $content['from_date'] . ' ' . \Yii::$app->general->getshift($content['from_shift']);
        $to_date = $content['to_date'] . ' ' . \Yii::$app->general->getshift($content['to_shift']);
        $col_data = $model->collectionData($from_date, $to_date, $bmc_code);
        $coll_data = [];
        foreach ($col_data as $dcs_col_data) {
            $data['dcs_name'] = Yii::$app->general->getforeignkey($dcs_col_data->dcsCode, 'dcs_name');
            $data['dcs_code'] = $dcs_col_data->dcs_code;
            $data['milk_type_code'] = $dcs_col_data->milk_type_code;
            $data['fat'] = $dcs_col_data->fat;
            $data['snf'] = $dcs_col_data->snf;
            $data['qty'] = $dcs_col_data->qty;
            $data['rtpl'] = $dcs_col_data->rtpl;
            $data['amount'] = $dcs_col_data->amount;
            $data['shift_code'] = $dcs_col_data->shift_code;
            $data['date_time_of_collection'] = $dcs_col_data->date_time_of_collection;
            $data['sample_no'] = $dcs_col_data->sample_no;
            $coll_data[] = $data;
        }
        return $this->response['data'] = $coll_data;
    }

}
