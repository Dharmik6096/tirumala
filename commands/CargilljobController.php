<?php
namespace app\commands;

use app\modules\tankermovement\models\TblMilkVehicleEntry;
use Yii;

class CargilljobController extends \yii\console\Controller {
    public function actionMilkReceiptSend(){
        try {
            var_dump("aaa");
            // $model = new TblMilkVehicleEntry();
            // $milkVehicalData = $model->getData();
            $milkVehicalData = TblMilkVehicleEntry::find()->where(['is','approval_statuss', null])->all();
            var_dump("123");
            if(!empty($milkVehicalData)){
                foreach($milkVehicalData as $milkVehical){
                    var_dump("hiiii");
                    // return true;
                }
            }
        } catch (\Throwable $ex) {
            // return true;
        }
    }
}
?>
