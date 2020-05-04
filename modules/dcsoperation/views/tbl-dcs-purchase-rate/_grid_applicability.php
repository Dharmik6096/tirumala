<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'width' => '200px',
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
        ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle',],
        ['attribute' => 'applicable_for', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerTypeFor, 'customer_desc');
        }, 'vAlign' => 'middle',],
        ['attribute' => 'applicable_code', 'vAlign' => 'middle',],
        ['attribute' => 'code_ex', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
        }, 'vAlign' => 'middle',],
        ['attribute' => 'mcc_name', 'value' => function($model) {
            if ($model->applicable_for == 'PLANT') {
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            } else if ($model->applicable_for == 'MCC') {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            } else if ($model->applicable_for == 'BMC') {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            } else if ($model->applicable_for == 'DCS') {
                return Yii::$app->general->getforeignkey($model->dcsName, 'dcs_name');
            } else {
                return Yii::$app->general->getforeignkey($model->customerMasterCode, 'customer_name');
            }
        }, 'vAlign' => 'middle',],
];

$grid_option = [
    'id' => 'purchase-rate-applicability-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>