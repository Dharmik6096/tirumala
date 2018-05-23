<?php

namespace app\modules\webservice\member\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\collection\models\TblMilkCollection;
use Yii;
use yii\helpers\Json;

class CollectionController extends ChildController {

    public $replace_array = [
        'replace_coloumn' => ['qty' => 'quantity', 'date_time_of_collection' => 'collection_date',
            'shift' => 'shift_code', 'rtpl' => 'rate', 'qlty_auto' => 'is_quality_auto',
            'qty_auto' => 'is_quantity_auto', 'milk_type_code' => 'animal_type_code'],
        'add_coloumn' => [
            'updated_at' => '',
            'updated_by' => '',
            'quantity_mode' => '',
            'converted_quantity' => '',
            'converted_quantity_mode' => '',
            'water' => '',
            'protein' => '',
            'density' => '',
            'lactose' => '',
        ]
    ];

    public function actionCollectionData() {
        $model = new TblMilkCollection();
        $model->setAttributes($this->post_data);
        $result = $model->getCollection($this->post_data['content']);
        $data = [];
        if (!empty($result)) {
            foreach ($result as $value) {
                $array_temp = [];
                $array_temp = $value->oldAttributes;
                $array_temp['animal_type_name'] = $value->milkTypeCode->animal_type_name;
                $array_temp['shift_code'] = $value->shiftCode->shift;
                $array_temp = $this->replaceColoumn($array_temp);
                $array_temp = $this->addColoumn($array_temp);
                $data[] = $array_temp;
            }
            $this->response['data'] = $data;
        }
        return $this->response;
    }

    public function actionCollectionDatewise() {
        $model = new TblMilkCollection();
        $model->setAttributes($this->post_data);
        $model->setAttributes($this->post_data['content']);
        $data = [];
        foreach ($model->getCollectionDatewise() as $collection) {
            $array_temp = [];
            $array_temp = $collection->oldAttributes;
            $array_temp['animal_type_name'] = $collection->milkTypeCode->animal_type_name;
            $array_temp['shift_code'] = $collection->shiftCode->shift;
            $array_temp = $this->replaceColoumn($array_temp);
            $data[] = $array_temp;
        }
        $this->response['data'] = $data;
        return $this->response;
    }

}
