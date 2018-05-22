<?php

namespace app\modules\webservice\vsp\v1\models;

use Yii;
use app\modules\organisation\models\TblDcs;

class Society extends TblDcs {
    public $village_name, $union_name;
    public function societyData() {
        return $this->find()->select(['dcs_code', 'dcs_name'])->where(['dcs_code' => $this->dcs_code])->one();
    }
    
    public function dcsInfo($encryptedmobile) {
        return $this->find()->where(['dcs_code' => $this->dcs_code,])->andWhere(['is_active' => 1])->one();
    }
}
