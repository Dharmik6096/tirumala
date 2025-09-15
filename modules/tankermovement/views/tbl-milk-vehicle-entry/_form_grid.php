<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'receipt_at'],
        ['attribute' => 'receipt_at_code', 'value' => function($model) {
            $response = Yii::$app->general->getColumnName($model->receipt_at);
            if (!empty($response['rel'])) {
                return Yii::$app->general->getforeignkey($model->{$response['rel'] . 'Dest'}, $response['name']) . '-' . strtoupper($model->receipt_at_code);
            }
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'receipt_at_code',
        'label' => Yii::t('app', 'Destination Code')],
        ['attribute' => 'receipt_at_code',
        'label' => (Yii::t('app', 'Destination Ref.Code')),
        'value' => function($model) {
            $response = Yii::$app->general->getColumnName($model->receipt_at);
            if (!empty($response['rel'])) {
                return Yii::$app->general->getforeignkey($model->{$response['rel'] . 'Dest'}, $response['ref_code']);
            }
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dispatch_from'],
        ['attribute' => 'dispatch_from_code', 'value' => function($model) {
            $response = Yii::$app->general->getColumnName($model->dispatch_from);
            if (!empty($response['rel'])) {
                return Yii::$app->general->getforeignkey($model->{$response['rel'] . 'Source'}, $response['name']) . '-' . strtoupper($model->dispatch_from_code);
            }
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dispatch_from_code',
        'label' => Yii::t('app', 'Source Code')],
        ['attribute' => 'dispatch_from_code',
        'label' => (Yii::t('app', 'Source Ref.Code')),
        'value' => function($model) {
            $response = Yii::$app->general->getColumnName($model->dispatch_from);
            if (!empty($response['rel'])) {
                return Yii::$app->general->getforeignkey($model->{$response['rel'] . 'Source'}, $response['ref_code']);
            }
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'transporter_code',
        'label' => Yii::t('app', 'Transporter'),
        'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->vehicleCode, ['transporter'], 'transporter_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'grn_no'],
        ['attribute' => 'vehicle_entry_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->vehicle_entry_date);
        }],
        ['attribute' => 'trip_code'],
        ['attribute' => 'vehicle_code',
        'label' => Yii::t('app', 'Vehicle No.'),
        'value' => function($model) {
            return !empty($model->vehicleCode) ? Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no') : $model->tanker_no;
        }, 'filter' => false],
        ['attribute' => 'qty'],
        [
        'attribute' => 'arrival_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->arrival_time);
        }, 'filter' => false],
        ['attribute' => 'gross_weight'],
        ['attribute' => 'tare_weight'],
        [
        'attribute' => 'tare_weight_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->tare_weight_time);
        }, 'filter' => false],
        ['attribute' => 'dock_no'],
];

$grid_option = [
    'id' => 'milk-vehicle-entry-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
        'milk-vehicle-entry-challan' => function ($url, $model) {
            $options = ['title' => 'Print Challan', 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-file-pdf"></i>', ['/tankermovement/tbl-milk-vehicle-entry/challan', 'id' => $model->milk_vehicle_entry_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
