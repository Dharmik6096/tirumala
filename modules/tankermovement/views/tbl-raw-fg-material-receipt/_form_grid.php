<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false],
        [
        'attribute' => 'receipt_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->receipt_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => false],
        ['attribute' => 'party_code', 'value' => function($model) {
            $sapVendorCode = Yii::$app->general->getforeignkey($model->partyMaster, 'sap_vendor_code');
            return Yii::$app->general->getforeignkey($model->partyMaster, 'party_name') . ($sapVendorCode ? ' (' . $sapVendorCode . ')' : '');
        }],
        ['attribute' => 'party_name'],
        ['attribute' => 'tanker_no'],
        ['attribute' => 'material_ref_code', 'label' => Yii::t('app', 'Material Ref Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->materialCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'material_code', 'label' => Yii::t('app', 'Material Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->materialCode, 'material_name');
        }],
        ['attribute' => 'document_type'],
        ['attribute' => 'document_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->document_date);
        }],
        ['attribute' => 'document_no'],
        ['attribute' => 'gross_weight', 'filter' => false],
        ['attribute' => 'gross_weight_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->gross_weight_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => false],
        ['attribute' => 'tare_weight', 'filter' => false],
        ['attribute' => 'tare_weight_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->tare_weight_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => false],
        ['attribute' => 'material_entry_type'],
        ['attribute' => 'remarks'],
        ['attribute' => 'dock_no'],
];

$grid_option = [
    'id' => 'material-receipt-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>


