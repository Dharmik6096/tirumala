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
    ['attribute' => 'source_ref_code', 'label' => (Yii::t('app', 'Source Ref. Code')), 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->source_type);
            $att = 'ref_code';
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'source_code', 'label' => (Yii::t('app', 'Source Code'))],
    ['attribute' => 'source', 'label' => (Yii::t('app', 'Source')), 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->source_type);
            $att = strtolower($model->source_type) == 'bmc' ? 'bmc_name' : (strtolower($model->source_type) == 'vendor' ? 'customer_name' : 'name');
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att) . '-' . strtoupper($model->source_type);
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dest_ref_code', 'label' => (Yii::t('app', 'Dest Ref. Code')), 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->destination_type);
            $att = 'ref_code';
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'destination_code', 'label' => (Yii::t('app', 'Dest Code'))],
    ['attribute' => 'destination', 'label' => (Yii::t('app', 'Destination')), 'value' => function($model) {
            $rel = Yii::$app->general->getDestRelation($model->destination_type);
            $att = strtolower($model->destination_type) == 'bmc' ? 'bmc_name' : (strtolower($model->destination_type) == 'vendor' ? 'customer_name' : 'name');
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) . '-' . strtoupper($model->destination_type);
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
    [
        'attribute' => 'transaction_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->transaction_datetime);
        }],
    ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'transfer_type',
        'value' => function($model) {
            return Yii::$app->dropdown->getRecords('transfer_type')['data'][$model->transfer_type];
        }, 'vAlign' => 'middle', 'filter' => FALSE],
    ['attribute' => 'fat'],
    ['attribute' => 'snf'],
    ['attribute' => 'qty'],
    ['attribute' => 'temp'],
    ['attribute' => 'is_rechilling', 'value' => function($model) {
            return ($model->is_rechilling == 0) ? 'No' : 'Yes';
        }, 'filter' => FALSE, 'visible' => true],
];

$grid_option = [
    'id' => 'collection-penalty-rate',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'view' => true,
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>