<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;

?>
<?php

$attribute = [
    ['attribute' => 'bmc_code', 'value' => 'bmc_code', 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'bmc_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => 'dcs_code', 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'dcs_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'route_name', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsCode, ['routeMapping'], 'route_name');
        }, 'vAlign' => 'middle', 'filter' => false],
            [
                'attribute' => 'date_time_of_collection',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
            //['attribute' => 'date_time_of_collection', 'value' => function($model){ return Yii::$app->controls->view_date($model->date_time_of_collection); }, 'vAlign' => 'middle', 'filter'=>false],
            ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'milk_type_code', 'value' => function($model) {
                    return isset($model->milkType) ? $model->milkType->animal_type_name : '';
                }, 'vAlign' => 'middle', 'visible' => false, 'filter' => false],
            ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                    return isset($model->milkQualityType) ? $model->milkQualityType->milk_quality_type_name : '';
                }, 'vAlign' => 'middle', 'visible' => false, 'filter' => false],
            ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'rtpl', 'value' => 'rtpl', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'amount', 'value' => 'amount', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'sample_no', 'vAlign' => 'middle'],
            ['attribute' => 'type_of_data_receive', 'vAlign' => 'middle'],
            ['attribute' => 'transporter_code', 'value' => function($model) {
                    return isset($model->transporter) ? $model->transporter->transporter_name : '';
                }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
            ['attribute' => 'vehicle_code', 'value' => function($model) {
                    return isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '';
                }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
            ['attribute' => 'collection_type', 'value' => function($model) {
                    return !empty($model->collection_type) ? ((Yii::$app->dropdown->getRecords('collection_type')['data'][$model->collection_type] != '') ? Yii::$app->dropdown->getRecords('collection_type')['data'][$model->collection_type] : '') : '';
                }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
            ['attribute' => 'remarks', 'value' => 'remarks', 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ];

        $grid_option = [
            'id' => 'bmc-collection',
            'attributes' => $attribute,
            'active_column' => false,
            'actions' => [
                'view' => TRUE,
                'update' => true,
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>