<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        [
        'attribute' => 'collection_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('approval_collection_type', $searchModel, 'collection_type'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('approval_collection_type')['data'][$model->collection_type]) ? Yii::$app->dropdown->getRecords('approval_collection_type')['data'][$model->collection_type] : '';
        }],
        ['attribute' => 'f_union_code', 'value' => function($model) {
            return ($model->collection_type == 1) ? Yii::$app->general->getmultiforeignkey($model->dcsCode, ['unionCode'], 'union_name') : Yii::$app->general->getmultiforeignkey($model->bmcCode, ['unionCode'], 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'f_plant_code', 'value' => function($model) {
            return ($model->collection_type == 1) ? Yii::$app->general->getmultiforeignkey($model->dcsCode, ['plantCode'], 'name') : Yii::$app->general->getmultiforeignkey($model->bmcCode, ['plantCode'], 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'f_mcc_code', 'value' => function($model) {
            return ($model->collection_type == 1) ? Yii::$app->general->getmultiforeignkey($model->dcsCode, ['mccPlantCode'], 'name') : Yii::$app->general->getmultiforeignkey($model->bmcCode, ['tblMccPlant'], 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'f_bmc_code', 'value' => function($model) {
            return ($model->collection_type == 1) ? Yii::$app->general->getmultiforeignkey($model->dcsCode, ['bmcCode'], 'bmc_name') : Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
        ['attribute' => 'f_dcs_code', 'value' => function($model) {
            return ($model->collection_type == 1) ? Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') : '';
        }, 'filter' => false],
        ['attribute' => 'code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
            return ($model->collection_type == 1) ? Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code') : Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'filter' => false],
        ['label' => 'Date', 'attribute' => 'date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date);
        }],
        ['attribute' => 'shift_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'filter' => false],
        ['attribute' => 'requested_by',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userAndroidCode, 'name');
        }],
        ['attribute' => 'approved_by',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }],
        ['label' => 'Approve Date', 'attribute' => 'approve_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->approve_date);
        }, 'filter' => false],
        ['label' => 'Allow Till Date', 'attribute' => 'allow_till_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->allow_till_date);
        }, 'filter' => false],
        ['attribute' => 'valid_hours'],
        [
        'attribute' => 'is_approve',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_approve'),
        'value' => function($model) {
            return ($model->is_approve == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
        }],
];

$grid_option = [
    'id' => 'collection-approval-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'approve' => function ($url, $model) {
            $name = $model->uuid;
            $class = ($model->is_approve == 1) ? 'link-disable' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Approve', 'class' => '' . $class, 'data-val' => $model->uuid, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-check"></i>', ['/collection/tbl-collection-approval/approve-collection', 'id' => $model->uuid], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index'], true);
?>

