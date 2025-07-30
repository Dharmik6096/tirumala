<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

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
        'value' => function ($model) {
            return Yii::$app->general->getmultiforeignkey($model->vehicleCode, ['transporter'], 'transporter_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false
    ],
    [
        'attribute' => 'bmc_code',
        'label' => Yii::t('app', 'Source Type'),
        'value' => function ($model) {
            return !empty($model->bmc_code) ? 'BMC' : 'PLANT';
        },
        'vAlign' => 'middle',
        'filter' => false
    ],
    [
        'attribute' => 'bmc_name',
        'label' => Yii::t('app', 'Source Name'),
        'value' => function ($model) {
            $sourceType = !empty($model->bmc_code) ? 'BMC' : 'PLANT';
            $sourceCode = !empty($model->bmc_code) ? $model->bmc_code : $model->plant_code;
            $response = Yii::$app->general->getColumnName($sourceType);
            if (!empty($response['rel']))
                return Yii::$app->general->getforeignkey($model->{$response['rel']}, $response['name']) . '-' . $sourceCode;
        }, 'vAlign' => 'middle', 'filter' => false
    ],
    [
        'attribute' => 'bmc_code',
        'label' => Yii::t('app', 'Source Code'),
        'value' => function ($model) {
            return !empty($model->bmc_code) ? $model->bmc_code : $model->plant_code;
        },
        'vAlign' => 'middle', 'filter' => false
    ],
    [
        'attribute' => 'bmc_code',
        'label' => (Yii::t('app', 'Source Ref.Code')),
        'value' => function ($model) {
            $sourceType = !empty($model->bmc_code) ? 'BMC' : 'PLANT';
            $response = Yii::$app->general->getColumnName($sourceType);
            if (!empty($response['rel']))
                return Yii::$app->general->getforeignkey($model->{$response['rel']}, $response['ref_code']);
        }, 'vAlign' => 'middle'
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
    ['attribute' => 'trip_code'],
    [
        'attribute' => 'parsing_no',
        'label' => Yii::t('app', 'Vehicle No.'),
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
        }
    ],
    ['attribute' => 'driver_name', 'label' => Yii::t('app', 'Driver Name'), 'enableSorting' => false],
    ['attribute' => 'mobile_no', 'label' => Yii::t('app', 'Driver Contact No.')],
    ['attribute' => 'challan_no'],
    ['attribute' => 'bmc_detail'],
    ['attribute' => 'kg_fat'],
    ['attribute' => 'kg_snf'],
    ['attribute' => 'total_qty'],
    ['attribute' => 'rejected_count'],
    ['attribute' => 'grn_no'],
    ['attribute' => 'trip_mode'],
    ['attribute' => 'trip_status', 'filter' => false],
    ['attribute' => 'trip_sub_status'],
    ['attribute' => 'is_auto_trip', 'label' => Yii::t('app', 'Is Partial Trip?'),
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_auto_trip'),
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_auto_trip');
        }
    ],
    ['attribute' => 'no_of_compartment', 'visible' => false],
    ['attribute' => 'vehicle_capacity', 'visible' => false],
    ['attribute' => 'remark'],
];

$grid_option = [
    'id' => 'vehicle-trip-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => function ($url, $model) {
            $class = ($model->trip_status != 'closed') ? '' : 'link-disable';
            $options = [
                'class' => 'edit-trip ' . $class,
                'title' => Yii::t('app', 'Edit Trip Detail'),
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
            ];

            $updatedUrl = Url::to(['/tankermovement/tbl-vehicle-trip/update', 'id' => $model->vehicle_trip_code]);
            if ($model->trip_for == 'salesparty') {
                $updatedUrl = Url::to([
                            '/tankermovement/tbl-vehicle-trip/update-with-party',
                            'id' => $model->vehicle_trip_code,
                            'type' => 'party'
                ]);
            }
            return Html::a('<i class="fa fa-pencil-alt"></i>', $updatedUrl, $options);
        },
        'generate-challan' => function ($url, $model) {
            $disable = ($model->trip_status == 'open') ? FALSE : TRUE;
            $disable = ($model->is_active == 1) ? $disable : TRUE;
            if ($disable) {
                return GhostHtml::a('<i class="fa fa-cog"></i>', ['/tankermovement/tbl-vehicle-trip/generate-challan'], ['class' => 'disabled']);
            } else {
                $options = [
                    'title' => Yii::t('app', 'Generate Challan'), 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top',
                    'title' => Yii::t('app', 'Generate Challan')
                ];
                return GhostHtml::a(
                                '<i class="fa fa-cog"></i>',
                                [
                            '/tankermovement/tbl-vehicle-trip/generate-challan',
                            'id' => $model->vehicle_trip_code
                                ],
                                $options
                );
            }
        },
        'print-challan' => function ($url, $model) {
            $disable = (in_array($model->trip_status, ['tankerfull', 'closed'])) ? FALSE : TRUE;
            $disable = ($model->is_active == 1) ? $disable : TRUE;
            if ($disable) {
                return GhostHtml::a('<i class="fas fa-file-pdf"></i>', ['/tankermovement/tbl-vehicle-trip/print-challan'], ['class' => 'disabled']);
            } else {

                $options = ['target' => '_blank', 'title' => Yii::t('app', 'Print Challan'), 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Print Challan')];
                return GhostHtml::a('<i class="fas fa-file-pdf"></i>', ['/tankermovement/tbl-vehicle-trip/print-challan', 'id' => $model->trip_code], $options);
            }
        },
        'close-trip' => function ($url, $model) {
            $disable = (in_array($model->trip_status, ['closed'])) ? TRUE : FALSE;
            $disable = ($model->is_active == 1) ? $disable : TRUE;
            if ($disable) {
                return GhostHtml::a_alert('<i class="fa fa-times"></i>', ['/tankermovement/tbl-vehicle-trip/close-trip'], ['class' => 'disabled']);
            } else {
                $options = ['title' => Yii::t('app', 'Close Trip'), 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Close Trip'), 'class' => 'close-trip', 'data-val' => $model->vehicle_trip_code, 'data-name' => $model->trip_code];
                return GhostHtml::a_alert('<i class="fa fa-times"></i>', ['/tankermovement/tbl-vehicle-trip/close-trip', 'id' => $model->vehicle_trip_code], $options);
            }
        },
        'inactive-trip' => function ($url, $model) {
            $disable = (!in_array($model->trip_status, ['generated'])) ? TRUE : FALSE;
            $disable = ($model->is_active == 1) ? $disable : TRUE;
            if ($disable) {
                return GhostHtml::a_alert('<i class="fa fa-ban"></i>', ['/tankermovement/tbl-vehicle-trip/inactive-trip'], ['class' => 'disabled']);
            } else {
                $options = ['title' => Yii::t('app', 'In-Active Trip'), 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'In-Active Trip'), 'class' => 'inactive-trip', 'data-val' => $model->vehicle_trip_code, 'data-name' => $model->trip_code];
                return GhostHtml::a_alert('<i class="fa fa-ban"></i>', ['/tankermovement/tbl-vehicle-trip/inactive-trip', 'id' => $model->vehicle_trip_code], $options);
            }
        },
        'inspection' => function ($url, $model) {
            $disabled = ($model->trip_status == 'closed' && $model->trip_sub_status == 'qa_pending') ? '' : 'disabled';
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/tankermovement/tbl-vehicle-qa-inspection/create', 'tripcode' => $model->trip_code], ['class' => $disabled]);
        },
        'map' => function ($url, $model) {
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'target' => '_blank', 'title' => 'View Map', 'data-val' => $model->trip_code];
            return GhostHtml::a('<i class="fa fa-map-marker-alt"></i>', ['/tankermovement/tbl-vehicle-trip/map', 'trip_code' => $model->trip_code], $options);
        },
        'vertical-chart' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'target' => '_blank', 'data-original-title' => 'View Map', 'data-val' => $model->trip_code];
            return GhostHtml::a('<i class="fa fa-bar-chart"></i>', ['/tankermovement/tbl-vehicle-trip/vertical-chart', 'trip_code' => $model->trip_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.close-trip',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to Close Trip \"'+name+'\" ?</span></div></div>',
        buttons: {
            'cancel': {
                            label: 'Cancel',
                            className: 'btn btn-danger'
              },
            'confirm': {
                            label: 'Ok',
                            className: 'btn btn-primary'
             }
        },
        callback: function(result) {
            if (result) {
              $('#loader').show();
                 $.ajax({
                        type: 'get',
                        url: '" . Url::to(['close-trip']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#vehicle-trip-list'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                        }
            });
       }
    }
        });
    });
 $(document).on('click','.inactive-trip',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to In-Active Trip \"'+name+'\" ?</span></div></div>',
        buttons: {
            'cancel': {
                            label: 'Cancel',
                            className: 'btn btn-danger'
              },
            'confirm': {
                            label: 'Ok',
                            className: 'btn btn-primary'
             }
        },
        callback: function(result) {
            if (result) {
              $('#loader').show();
                 $.ajax({
                        type: 'get',
                        url: '" . Url::to(['inactive-trip']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#vehicle-trip-list'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                            }
                        }
            });
       }
    }
        });
    });    
});";
$this->registerJs($script, View::POS_END, 'vehicle-trip-index');
