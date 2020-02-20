<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'rate_code', 'value' => 'rate_code', 'filter' => false],
    ['attribute' => 'rate_desc', 'label' => Yii::t('app', 'Rate Desc.'), 'value' => function($model) {
            $desc = strtolower($model->rate_type) == 'member' ? Yii::$app->general->getforeignkey($model->rateDescCode, 'description') : Yii::$app->general->getforeignkey($model->dcsRateDescCode, 'description');
            $refCode = strtolower($model->rate_type) == 'member' ? Yii::$app->general->getforeignkey($model->rateDescCode, 'reference_code') : '';
            return !empty($desc) ? $refCode . '(' . $desc . ')' : $refCode;
        }, 'filter' => false],
    ['attribute' => 'recalc_for', 'value' => 'recalc_for', 'filter' => false],
    [
        'attribute' => 'from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }, 'filter' => false],
    ['attribute' => 'from_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShiftId, 'shift');
        }, 'vAlign' => 'middle', 'filter' => '<span class="shift">' . Yii::$app->dropdown->dropdownfilter('shift', $searchModel, 'from_shift', Yii::t('app', 'Select'), 'form-control shift') . '</span>'],
    ['attribute' => 'recalc_type', 'value' => function($model) {
            return $model->recalc_type;
        }, 'filter' => false],
    [
        'attribute' => 'to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'filter' => false],
    ['attribute' => 'to_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShiftId, 'shift');
        }, 'vAlign' => 'middle', 'filter' => '<span class="shift">' . Yii::$app->dropdown->dropdownfilter('shift', $searchModel, 'to_shift', Yii::t('app', 'Select'), 'form-control shift') . '</span>'], ['attribute' => 'recalc_type', 'value' => function($model) {
            return $model->recalc_type;
        }, 'filter' => false],
//    ['header' => 'DSK', 'attribute' => 'dcs_code', 'value' => function($model) {
//            return strlen($model->dcs_code) > 30 ? substr($model->dcs_code, 0, 30) . '...' : $model->dcs_code;
//        },
//        'contentOptions' => function($model) {
//            return ['title' => $model->dcs_code];
//        },
//        'filter' => false,],
];

$grid_option = [
    'id' => 'milk-receipt-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'details' => function ($url, $model) {
            $prms['TblRateRecalculationSearch']['from_date'] = $model->from_date;
            $prms['TblRateRecalculationSearch']['to_date'] = $model->to_date;
            $prms['TblRateRecalculationSearch']['recalc_for'] = $model->recalc_for;
            $prms['TblRateRecalculationSearch']['rate_code'] = $model->rate_code;
            $prms['TblRateRecalculationSearch']['from_shift'] = $model->from_shift;
            $prms['TblRateRecalculationSearch']['to_shift'] = $model->to_shift;
            $prms['TblRateRecalculationSearch']['bmc_code'] = $model->bmc_code;
            $url = ['/dcsoperation/tbl-rate-recalculation/view'] + $prms;
            $options = ['data-name' => $model->dcs_code, 'data-val' => $model->dcs_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Details', 'class' => ''];
            return GhostHtml::a('<i class="fa fa-eye"></i>', $url, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
