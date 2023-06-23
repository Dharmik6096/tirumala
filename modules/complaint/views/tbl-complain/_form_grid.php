<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'complain_code'],
        ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'contact_person'],
        ['attribute' => 'mobile_no'],
        ['attribute' => 'complain_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->complain_datetime);
        }],
        ['attribute' => 'complain_type_code', 'value' => 'complainFors.complain_type', 'filter' => false, 'visible' => false],
        ['attribute' => 'affects_data', 'filter' => array('1' => 'Yes', '0' => 'No'), 'visible' => false,
        'value' => function($model) {
            return $model->affects_data == 1 ? 'Yes' : 'No';
        }],
        ['attribute' => 'physical_damage', 'filter' => array('1' => 'Yes', '0' => 'No'), 'visible' => false,
        'value' => function($model) {
            return $model->physical_damage == 1 ? 'Yes' : 'No';
        }],
        ['attribute' => 'asset_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->asset, 'asset_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'serial_number', 'visible' => true, 'filter' => false],
        [
        'attribute' => 'complain_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('complain_status', $searchModel, 'complain_status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('complain_status')['data'][$model->complain_status]) ? Yii::$app->dropdown->getRecords('complain_status')['data'][$model->complain_status] : '';
        }],
//        ['attribute' => 'assign_to', 'value' => function ($model) {
//            return !empty($model->contactDetailsCode) ? $model->contactDetailsCode->firstname . '(' . $model->contactDetailsCode->mobile_no . ')' : 'N/A';
//        }, 'visible' => true, 'filter' => false],
];

$grid_option = [
    'id' => 'complaint',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
        'edit' => function ($url, $model) {
            $url = Url::to(['tbl-complain/update', 'id' => $model->complain_code]);
            $status = $model->getComplainStatus($model->complain_code);
            $class = (!isset($status)) ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->complain_code, 'data-name' => ''];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
        'delete' => ['option' => 'contact_person,complain_code,tbl-complain/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>