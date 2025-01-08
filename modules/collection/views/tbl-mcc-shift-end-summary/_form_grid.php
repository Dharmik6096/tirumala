<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\View;
?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'value' => 'bmc_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],

    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'value' => 'dcs_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'dcs_ref_code', 'label' => (Yii::t('app', 'DCS Ref.Code')), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
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
    ['attribute' => 'shift_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }
    ],
    ['attribute' => 'milk_type_code', 'filter' => false],
    ['attribute' => 'quantity', 'filter' => false],
    ['attribute' => 'fat', 'filter' => false],
    ['attribute' => 'snf', 'filter' => false],
    ['attribute' => 'p_fat', 'filter' => false],
    ['attribute' => 'p_snf', 'filter' => false],
    ['attribute' => 'p_quantity', 'filter' => false],
    ['attribute' => 'd_fat', 'filter' => false],
    ['attribute' => 'd_snf', 'filter' => false],
    ['attribute' => 'd_quantity', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-mcc-shift-end-summary-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>