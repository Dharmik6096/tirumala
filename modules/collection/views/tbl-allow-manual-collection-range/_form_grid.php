<?php

use kartik\grid\GridView;
use yii\helpers\Html;

?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['label' => Yii::t('app', 'Plant Code'), 'attribute' => 'plant_code', 'filter' => false, 'visible' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['label' => Yii::t('app', 'MCC Code'), 'attribute' => 'mcc_plant_code', 'filter' => false, 'visible' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['label' => Yii::t('app', 'BMC Code'), 'attribute' => 'bmc_code', 'filter' => false],
        ['label' => Yii::t('app', 'BMC Ref. Code'), 'attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => true, 'filter' => false],
        ['label' => Yii::t('app', 'DCS Code'), 'attribute' => 'dcs_code', 'filter' => false],
        ['label' => Yii::t('app', 'DCS Ref. Code'), 'attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'from_date', 'filter' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
        ['attribute' => 'from_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShift, 'shift');
        }, 'filter' => FALSE],
        ['attribute' => 'to_date', 'filter' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
        ['attribute' => 'to_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShift, 'shift');
        }, 'filter' => FALSE],
        ['attribute' => 'table_name',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('table_name', $searchModel, 'table_name'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('table_name')['data'][$model->table_name]) ? Yii::$app->dropdown->getRecords('table_name')['data'][$model->table_name] : '';
        },],
        ['attribute' => 'entry_type', 'filter' => false],
        ['attribute' => 'application_type', 'filter' => false],
        ['attribute' => 'is_weight_manual',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_weight_manual'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual] : '';
        },],
        ['attribute' => 'is_quality_manual',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_quality_manual'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual] : '';
        },],
        ['attribute' => 'is_approved', 'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_approved]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_approved] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_approved')],
        ['attribute' => 'approval_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('manual_approve_status', $searchModel, 'approval_status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status]) ? Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status] : '';
        }],
        ['attribute' => 'remark', 'filter' => false],
        ['attribute' => 'originating_org_type', 'filter' => false],
        ['attribute' => 'action_perform', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('action_perform', $searchModel, 'action_perform'),],
        ['attribute' => 'created_by', 'value' => function($m) {
            $createdBy = $m->createdBy;
            if (!empty($createdBy)) {
                return !empty($createdBy->contact_person) ? $createdBy->contact_person : $createdBy->firstname;
            }
            return null;
        }, 'filter' => FALSE],
];

$grid_option = [
    'id' => 'allow-collection-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'views' => function($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Manual Collection Request View'];
            return Html::a('<i class="fa fa-eye"></i>', ['/collection/tbl-allow-manual-collection-range/view', 'id' => $model->allow_manual_collection_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
