<?php

namespace app\modules\webservice\amcs\v1\controllers;

use yii\web\Controller;
use app\modules\webservice\controllers\ChildController;

class CleaningCalibrationController extends ChildController {

    public function actionSaveData() {
        $param = [];
        $content = $this->post_data['content'];
        $id = [];
        foreach ($content as $data) {
            $param[] = $data['date'] . ' 00:00:00';
            $param[] = $data['shift'];
            $param[] = isset($data['vlcc_id']) ? $data['vlcc_id'] : NULL;
            if ($data['type'] == 'caliberation') {
                $sp = 'sp_MA_Calibration';
                $param[] = $data['milk_type'];
            } else {
                $sp = 'sp_MA_Cleaning';
            }
            $param[] = NULL; //qty $data[''];
            $param[] = NULL; //fat $data[''];
            $param[] = NULL; //snf $data[''];
            $param[] = NULL; //amt $data[''];
            $param[] = 'AMCS CREAMY';
            $this->getSpData($sp, $param);
            $id[] = $data['id'];
        }

        //Exec TIRUMALA.[dbo].[sp_MA_Cleaning] @dtdate, @shift, @vlccid, @qty, @fat, @snf, @amt, 'GPRSTrans'
        //Exec TIRUMALA.[dbo].[sp_MA_Calibration] @dtdate, @shift, @vlccid, @milktype, @qty, @fat, @snf, @amt, 'GPRSTrans'

        return $this->response['data'] = $id;
    }

}
