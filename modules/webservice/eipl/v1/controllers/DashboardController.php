<?php

namespace app\modules\webservice\eipl\v1\controllers;

use app\modules\webservice\eipl\controllers\MasterController;
use Yii;
use app\modules\webservice\eipl\v1\V1;
use app\modules\webservice\components\EiplRequest;
use yii\helpers\Json;
use app\modules\webservice\eipl\models\TblDpuCollectionHoData;

class DashboardController extends MasterController {

    public function actionCalender() {
        $jsonArray = [];
        $jsonArray[] = [
            "milkType" => "Cow",
            "fat" => "",
            "snf" => "",
            "clr" => "",
            "qty" => "",
            "rate" => "",
            "amount" => "",
            "auto" => true,
            "date" => "01-10-2019",
            "shift" => "morning"
        ];
        $jsonArray[] = [
            "milkType" => "Cow",
            "fat" => "",
            "snf" => "",
            "clr" => "",
            "qty" => "",
            "rate" => "",
            "amount" => "",
            "auto" => true,
            "date" => "01-10-2019",
            "shift" => "morning"
        ];

        $jsonArray[] = [
            "milkType" => "Cow",
            "fat" => "",
            "snf" => "",
            "clr" => "",
            "qty" => "",
            "rate" => "",
            "amount" => "",
            "auto" => true,
            "date" => "01-10-2019",
            "shift" => "evening"
        ];

        $jsonArray[] = [
            "milkType" => "Cow",
            "fat" => "",
            "snf" => "",
            "clr" => "",
            "qty" => "",
            "rate" => "",
            "amount" => "",
            "auto" => true,
            "date" => "01-10-2019",
            "shift" => "evening"
        ];

        $jsonArray[] = [
            "milkType" => "Cow",
            "fat" => "",
            "snf" => "",
            "clr" => "",
            "qty" => "",
            "rate" => "",
            "amount" => "",
            "auto" => true,
            "date" => "02-10-2019",
            "shift" => "morning"
        ];
        $jsonArray[] = [
            "milkType" => "Cow",
            "fat" => "",
            "snf" => "",
            "clr" => "",
            "qty" => "",
            "rate" => "",
            "amount" => "",
            "auto" => true,
            "date" => "02-10-2019",
            "shift" => "morning"
        ];

        $jsonArray[] = [
            "milkType" => "Cow",
            "fat" => "",
            "snf" => "",
            "clr" => "",
            "qty" => "",
            "rate" => "",
            "amount" => "",
            "auto" => true,
            "date" => "02-10-2019",
            "shift" => "evening"
        ];

        $jsonArray[] = [
            "milkType" => "Cow",
            "fat" => "",
            "snf" => "",
            "clr" => "",
            "qty" => "",
            "rate" => "",
            "amount" => "",
            "auto" => true,
            "date" => "02-10-2019",
            "shift" => "evening"
        ];
        $setArray = [];
        foreach ($jsonArray as $j) {
            $setArray[$j['date']][$j['shift']][] = $j;
        }
        $finalArray = [];
        $i = 0;
        foreach ($setArray as $key => $value) {
            $finalArray[$i] = $value;
            $finalArray[$i]['date'] = $key;
            $i++;
        }
        $this->response->setData($finalArray);
        return $this->response;
    }

}
