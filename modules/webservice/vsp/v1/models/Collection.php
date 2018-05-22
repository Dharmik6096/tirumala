<?php

namespace app\modules\webservice\vsp\v1\models;

use Yii;
use app\modules\collection\models\TblMilkCollection;

class Collection extends TblMilkCollection {

    public function collectionData($shift, $date) {
        return $this->find()->select(['member_code', 'name', 'milk_type_code', 'fat', 'snf', 'qty', 'rtpl', 'amount','shift','date_time_of_collection','sample_no'])
                        ->where(['dcs_code' => $this->dcs_code, 'shift' => $shift, 'CONVERT(date,date_time_of_collection)' => $date])->all();
    }
    
    public function memberCollectionData($member_code, $from_date, $to_date) {
        return $this->find()->select(['member_code', 'name', 'milk_type_code', 'fat', 'snf', 'qty', 'rtpl', 'amount','shift','date_time_of_collection','sample_no'])
                        ->where(['dcs_code' => $this->dcs_code])
                        ->andFilterWhere(['>=', 'CONVERT(date,date_time_of_collection)', $from_date])
                        ->andFilterWhere(['<=', 'CONVERT(date,date_time_of_collection)', $to_date])
                        ->andFilterWhere(['like', 'member_code', $member_code])
                        ->all();
    }

}


