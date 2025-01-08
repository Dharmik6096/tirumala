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
    ['attribute' => 'temperature', 'filter' => false],
    ['attribute' => 'taste', 'filter' => false],
    ['attribute' => 'alcohol', 'filter' => false],
    ['attribute' => 'cob', 'filter' => false],
    ['attribute' => 'glucose', 'filter' => false],
    ['attribute' => 'salt', 'filter' => false],
    ['attribute' => 'sugar', 'filter' => false],
    ['attribute' => 'urea', 'filter' => false],
    ['attribute' => 'starch', 'filter' => false],
    ['attribute' => 'rosolic_acid', 'filter' => false],
    ['attribute' => 'h2o2', 'filter' => false],
    ['attribute' => 'formalin', 'filter' => false],
    ['attribute' => 'detergent', 'filter' => false],
    ['attribute' => 'nitrate_comp', 'filter' => false],
    ['attribute' => 'ammonium_comp', 'filter' => false],
    ['attribute' => 'acidity', 'filter' => false],
    ['attribute' => 'mbrt', 'filter' => false],
    ['attribute' => 'malto_dextrin', 'filter' => false],
    ['attribute' => 'protein_chainna', 'filter' => false],
    ['attribute' => 'br_value', 'filter' => false],
    ['attribute' => 'rm_value', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-mcc-shift-end-summary-adulteration-test-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>