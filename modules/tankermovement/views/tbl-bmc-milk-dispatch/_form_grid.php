<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\User;

$updateTransaction = User::canRoute('/tankermovement/tbl-bmc-milk-dispatch/update-transaction');
$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    [
        'attribute' => 'transporter_code',
        'label' => Yii::t('app', 'Transporter'),
        'value' => function ($model) {
            return Yii::$app->general->getmultiforeignkey($model->vehicleCode, ['transporter'], 'transporter_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false
    ],
    [
        'attribute' => 'source_org_type',
        'label' => Yii::t('app', 'Source Type'),
        'value' => function ($model) {
            return strtoupper($model->source_org_type);
        },
        'vAlign' => 'middle',
        'filter' => false
    ],
    [
        'attribute' => 'source_org_type',
        'label' => Yii::t('app', 'Source Name'),
        'value' => function ($model) {
            $rel = Yii::$app->general->getDestRelation($model->source_org_type);
            $att = strtolower($model->source_org_type) == 'bmc' ? 'bmc_name' : (strtolower($model->source_org_type) == 'vendor' ? 'customer_name' : (strtolower($model->source_org_type) == 'party' ? 'party_name' : 'name'));
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->$rel, $att) . '-' . $model->source_org_code;
        }, 'vAlign' => 'middle', 'filter' => false
    ],
    [
        'attribute' => 'source_org_code',
        'label' => Yii::t('app', 'Source Code'),
        'value' => function ($model) {
            return strtoupper($model->source_org_code);
        },
        'vAlign' => 'middle',
        'filter' => false
    ],
    [
        'attribute' => 'source_org_code',
        'label' => (Yii::t('app', 'Source Ref.Code')),
        'value' => function ($model) {
            $rel = Yii::$app->general->getDestRelation($model->source_org_type);
            if (strtolower($model->source_org_type) != 'party' && !empty($rel))
                return Yii::$app->general->getforeignkey($model->$rel, 'ref_code');
        }, 'vAlign' => 'middle'
    ],
    [
        'attribute' => 'destination_type',
        'label' => Yii::t('app', 'Dest. Type'),
        'value' => function ($model) {
            return strtoupper($model->destination_type);
        },
        'vAlign' => 'middle',
        'filter' => false
    ],
    [
        'attribute' => 'destination_type',
        'label' => Yii::t('app', 'Dest. Name'),
        'value' => function ($model) {
            $rel = Yii::$app->general->getDestRelation($model->destination_type);
            $destinationType = strtolower($model->destination_type);
            $att = ($destinationType == 'bmc') ? 'bmc_name' : (($destinationType == 'party') ? 'party_name' : (($destinationType == 'vendor') ? 'customer_name' : 'name'));
            if (!empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) . '-' . strtoupper($model->destination_code);
        }, 'vAlign' => 'middle', 'filter' => false
    ],
    ['attribute' => 'destination_code', 'filter' => false],
    [
        'attribute' => 'destination_code',
        'label' => (Yii::t('app', 'Dest. Ref.Code')),
        'value' => function ($model) {
            $rel = Yii::$app->general->getDestRelation($model->destination_type);
            if (strtolower($model->destination_type) != 'party' && !empty($rel))
                return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false
    ],
    [
        'attribute' => 'transaction_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->transaction_date);
        }
    ],
    [
        'attribute' => 'from_date',
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }, 'filter' => false
    ],
    [
        'attribute' => 'from_shift_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->fromShiftCode, 'shift');
        }, 'filter' => false
    ],
    [
        'attribute' => 'to_date',
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'filter' => false
    ],
    [
        'attribute' => 'to_shift_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->toShiftCode, 'shift');
        }, 'filter' => false
    ],
    [
        'attribute' => 'parsing_no',
        'label' => Yii::t('app', 'Vehicle No.'),
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
        }
    ],
    ['attribute' => 'trip_code'],
    ['attribute' => 'challan_no'],
    ['attribute' => 'driver_name'],
    ['attribute' => 'driver_contact_no'],
    [
        'attribute' => 'vehicle_in_time',
        'value' => function ($model) {
            return Yii::$app->controls->view_time($model->vehicle_in_time);
        }
    ],
    [
        'attribute' => 'vehicle_out_time',
        'value' => function ($model) {
            return Yii::$app->controls->view_time($model->vehicle_out_time);
        }
    ],
    ['attribute' => 'tested_by', 'visible' => true, 'filter' => false],
    ['attribute' => 'remarks'],
    [
        'attribute' => 'is_last_destination',
        'value' => function ($model) {
            return $model->is_last_destination == 1 ? 'Yes' : 'No';
        }, 'filter' => false
    ],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'Channel'), 'value' => function ($model) {
            return Yii::$app->general->getmultiforeignkey($model->bmcCode, ['channelMaster'], 'channel_desc');
        }, 'visible' => true, 'filter' => false],
];

$grid_option = [
    'id' => 'bmc-milk-dispatch-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
        'edit' => function ($url, $model) {
            $canEdit = $model->getTripDetailsCount();
            $type = strtolower($model->source_org_type);
            $url = $canEdit && $type == 'bmc' ? '/tankermovement/tbl-bmc-milk-dispatch/create' : ($canEdit && $type == 'plant' ? '/tankermovement/tbl-bmc-milk-dispatch/create-plant-dispatch' : '');
            $options = ['class' => $url ? '' : 'link-disable', 'title' => Yii::t('app', 'Edit'), 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'aria-label' => 'Edit', 'onclick' => $url ? null : 'return false;'];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', [$url, 'id' => $model->bmc_milk_dispatch_code], $options);
        },
        'tanker-dispatch-challan' => function ($url, $model) {
            $options = ['title' => 'Print Challan', 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/tankermovement/tbl-bmc-milk-dispatch/challan', 'id' => $model->bmc_milk_dispatch_code], $options);
        },
        'edit-txn' => function ($txnUrl, $model) use ($updateTransaction) {
            if (!$updateTransaction) {
                return '';
            }
            $canEdit = $model->getlastDestTripDetail();
            $type = strtolower($model->source_org_type);
            $txnUrl = $canEdit && $type == 'bmc' ? '/tankermovement/tbl-bmc-milk-dispatch/create' : ($canEdit && $type == 'plant' ? '/tankermovement/tbl-bmc-milk-dispatch/create-plant-dispatch' : '');
            $options = ['class' => $txnUrl ? '' : 'link-disable', 'title' => Yii::t('app', 'Edit'), 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'aria-label' => 'Edit Txn', 'onclick' => $txnUrl ? null : 'return false;'];
            return GhostHtml::a('<i class="fa fa-edit"></i>', [$txnUrl, 'id' => $model->bmc_milk_dispatch_code, 'txnEdit' => TRUE], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
