<?php

namespace app\modules\webservice\vsp\v1\models;

use Yii;
use app\modules\webservice\v1\models\TblAppActivation;
//use app\modules\dcsoperation\models\TblDcsPaymentCycle;
//use app\modules\payment\models\TblDcsPaymentCycle;
//use app\modules\dcsoperation\models\TblFarmerBill;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;

class RateChart extends TblPurchaseRateDetails {

    public function rateChart($purchase_code,$milk_type_code){
        
            return $this->find()->select(['fat', 'snf', 'rtpl','purchase_rate_code'])
                        ->where(['purchase_rate_code' => $purchase_code, 'milk_type_code' => $milk_type_code])->all();
    }

}


