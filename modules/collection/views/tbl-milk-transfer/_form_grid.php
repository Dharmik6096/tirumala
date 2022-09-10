<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'Source Ref. Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->sourceCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'source_code', 'label' => (Yii::t('app', 'Source Code')),],
    ['attribute' => 'source', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->sourceCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'Dest Ref. Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->destCode, 'ref_code');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'destination_code', 'label' => (Yii::t('app', 'Dest Code')),],
    ['attribute' => 'destination', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->destCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'transaction_id'],
    [
        'attribute' => 'from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
    ['attribute' => 'from_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
    ['attribute' => 'to_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'transfer_type',
//        'filter' => Yii::$app->dropdown->dropdownfilterStatic('transfer_type', $searchModel, 'transfer_type'),
        'value' => function($model) {
            return Yii::$app->dropdown->getRecords('transfer_type')['data'][$model->transfer_type];
        }, 'vAlign' => 'middle', 'filter' => FALSE],
    ['attribute' => 'fat'],
    ['attribute' => 'snf'],
    ['attribute' => 'qty'],
    ['attribute' => 'temp'],
    ['attribute' => 'remarks', 'visible' => TRUE],
];

$grid_option = [
    'id' => 'collection-penalty-rate',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>