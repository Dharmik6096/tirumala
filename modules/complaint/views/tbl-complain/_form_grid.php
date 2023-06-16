<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

//$complaint_type = array('None' => 'None', 'Network' => 'Network', 'Modem' => 'Modem', 'Eco' => 'Eko', 'Wing scale' => 'Wing scale');
//$status = Yii::$app->dropdown->getRecords('complaint_status')['data'];
?>

<?php
$attribute = [
    'complaint_code',
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
//        ['attribute' => 'date',
//        'filterType' => GridView::FILTER_DATE,
//        'filterWidgetOptions' => [
//            'pluginOptions' => ['format' => 'dd-mm-yyyy',
//                'autoclose' => true]
//        ],
//        'value' => function($model) {
//            return Yii::$app->controls->view_date($model->date);
//        }],
//        ['attribute' => 'complaint_type', 'value' => 'complaintProduct.cmpl_product_name', 'filter' => false, 'visible' => false],
//        ['attribute' => 'affects_data', 'filter' => array('1' => 'Yes', '0' => 'No'), 'visible' => false,
//        'value' => function($model) {
//            return $model->affects_data == 1 ? 'Yes' : 'No';
//        }], ['attribute' => 'physical_damage', 'filter' => array('1' => 'Yes', '0' => 'No'), 'visible' => false,
//        'value' => function($model) {
//            return $model->physical_damage == 1 ? 'Yes' : 'No';
//        }],
//        ['attribute' => 'attachment', 'visible' => false, 'filter' => false],
//        ['attribute' => 'asset_code', 'value' => function ($model) {
//            return Yii::$app->general->getforeignkey($model->asset, 'asset_name');
//        }, 'visible' => true, 'filter' => false],
//        ['attribute' => 'serial_number', 'visible' => true, 'filter' => false],
//        ['attribute' => 'status', 'filter' => $status,
//        'value' => function($model) use($status) {
//            $approvalStatus = Yii::$app->general->getforeignkey($model->approvalLog, 'approval_status');
//            return in_array($model->status, array_keys($status)) ? ($approvalStatus != '' && $approvalStatus == 0 && $model->status == 3 ? Yii::t('app', 'Closed') : $status[(int) $model->status]) : '';
//        }],
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
//        'edit' => function ($url, $model) {
//            $checkApproval = Yii::$app->general->checkApprovalStage('TblComplaintApprovalLog', 'complaint_code', $model->complaint_code, 'complaint');
//            $url = Url::to(['tbl-complaint-activity/create', 'id' => $model->complaint_code]);
//            $status = $model->getComplaintStatus($model->complaint_code);
//            $class = (!isset($status) && ($model->physical_damage != 1 || (isset($checkApproval['allowEdit']) && $checkApproval['allowEdit']))) ? '' : 'link-disable';
//            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->complaint_code, 'data-name' => ''];
//            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
//        },
//        'approve' => function ($url, $model) {
//            $checkApproval = Yii::$app->general->checkApprovalStage('TblComplaintApprovalLog', 'complaint_code', $model->complaint_code, 'complaint');
//            $url = Url::to(['approve', 'id' => $model->complaint_code, 'level_count' => $checkApproval['userLevel']]);
//            $class = ($model->physical_damage == 1 && (isset($checkApproval['allowApprove']) && $checkApproval['allowApprove'])) ? '' : 'link-disable';
//            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Approve/Reject', 'class' => '' . $class, 'data-val' => $model->complaint_code, 'data-name' => ''];
//            return GhostHtml::a('<i class="fa fa-check"></i>', $url, $options);
//        },
//        'assgin_complaint' => function ($url, $model) use($status) {
//            $url = Url::to(['assign-complaint', 'complaint_code' => $model->complaint_code]);
//            $class = strtolower($status[(int) $model->status]) == 'create' ? '' : ' link-disable ';
//            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Assign', 'class' => 'assign-complaint ' . $class, 'data-complaint_code' => $model->complaint_code, 'data-name' => ''];
//            return GhostHtml::a_alert('<i class="fa fa-user"></i>', $url, $options);
//        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>