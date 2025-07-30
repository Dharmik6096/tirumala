<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use yii\helpers\Url;
use yii\web\View;

$this->title = Yii::$app->label->title('view', 'Vehicle Trip');
$is_button_visible = true;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                $sourceType = !empty($model->bmc_code) ? 'BMC' : 'PLANT';
                $rel = Yii::$app->general->getDestRelation($sourceType);
                $att = strtolower($sourceType) == 'bmc' ? 'bmc_name' : (
                        strtolower($sourceType) == 'vendor' ? 'customer_name' : (
                        strtolower($sourceType) == 'party' ? 'party_name' : 'name'
                        )
                        );
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'union_code',
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:' . (!empty($model->bmc_code) ? '30%' : '80%')],
                            ],
                            [
                                'attribute' => 'mcc_plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                                'visible' => !empty($model->bmc_code) ? true : false
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'Source Type'),
                                'value' => $sourceType,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'Source Name'),
                                'value' => Yii::$app->general->getforeignkey($model->$rel, $att),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'Source Code'),
                                'value' => !empty($model->bmc_code) ? $model->bmc_code : $model->plant_code,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'label' => (Yii::t('app', 'Source Ref.Code')),
                                'value' => Yii::$app->general->getforeignkey($model->$rel, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'transporter_code',
                                'value' => Yii::$app->general->getmultiforeignkey($model->vehicleCode, ['transporter'], 'transporter_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'vehicle_code',
                                'value' => Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no'),
                                'label' => Yii::t('app', 'Vehicle No.'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'transaction_date',
                                'value' => Yii::$app->controls->view_date($model->transaction_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'trip_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'vehicle_code',
                                'label' => Yii::t('app', 'Driver Name'),
                                'value' => Yii::$app->general->getforeignkey($model->vehicleCode, 'driver_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'vehicle_code',
                                'label' => Yii::t('app', 'Driver Contact No.'),
                                'value' => Yii::$app->general->getforeignkey($model->vehicleCode, 'driver_contact_no'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'trip_mode',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'trip_status',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'grn_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'is_active',
                                'label' => 'Status',
                                'format' => 'html',
                                'value' => GeneralFunctions::getRecordStatus($model->is_active),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'trip_sub_status',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'no_of_compartment',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'vehicle_capacity',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'remark',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                ];

                // View file rendering the widget
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $attributes,
                    'mode' => 'view',
                    'bordered' => true,
                    'striped' => false,
                    'responsive' => true,
                    'hAlign' => 'left',
                    'vAlign' => 'top',
                    'deleteOptions' => [// your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Trip Detail') ?></h5></div> -->
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Trip Detail') ?></h4>
            </div>


            <div class="form-grid">
                <?php
                $attribute = [
                    [
                        'attribute' => 'transaction_datetime',
                        'value' => function ($model) {
                            return Yii::$app->controls->view_datetime($model->transaction_datetime);
                        }
                    ],
                    ['attribute' => 'source_org_code',],
                    ['attribute' => 'source_org_type', 'value' => function ($model) {
                            $response = Yii::$app->general->getColumnName($model->source_org_type);
                            if (!empty($response['rel'])) {
                                $data = $model->{$response['rel'] . 'Source'};
                                if (!empty($data)) {
                                    return $data->{$response['name']} . '-' . strtoupper($model->source_org_type);
                                }
                            }
                        }, 'filter' => false],
                    [
                        'attribute' => 'source_org_code',
                        'label' => (Yii::t('app', 'Source Ref.Code')),
                        'value' => function ($model) {
                            $response = Yii::$app->general->getColumnName($model->source_org_type);
                            if (!empty($response['rel'])) {
                                $data = $model->{$response['rel'] . 'Source'};
                                if (!empty($data)) {
                                    return $data->{$response['ref_code']};
                                }
                            }
                        }, 'filter' => false
                    ],
                    ['attribute' => 'destination_code',],
                    ['attribute' => 'destination_type', 'value' => function ($model) {
                            $response = Yii::$app->general->getColumnName($model->destination_type);
                            if (!empty($response['rel'])) {
                                $data = $model->{$response['rel'] . 'Dest'};
                                if (!empty($data)) {
                                    return $data->{$response['name']} . '-' . strtoupper($model->destination_type);
                                }
                            }
                        }, 'filter' => false],
                    [
                        'attribute' => 'destination_code',
                        'label' => (Yii::t('app', 'Dest. Ref.Code')),
                        'value' => function ($model) {
                            $response = Yii::$app->general->getColumnName($model->destination_type);
                            if (!empty($response['rel'])) {
                                $data = $model->{$response['rel'] . 'Dest'};
                                if (!empty($data)) {
                                    return $data->{$response['ref_code']};
                                }
                            }
                        }, 'filter' => false
                    ],
                    [
                        'attribute' => 'arrival_time',
                        'label' => (Yii::t('app', 'GateIn Time')),
                        'value' => function ($model) {
                            return Yii::$app->controls->view_datetime($model->arrival_time);
                        }
                    ],
                    ['attribute' => 'in_remarks', 'label' => (Yii::t('app', 'GateIn Remarks')),],
                    [
                        'attribute' => 'departure_time',
                        'label' => (Yii::t('app', 'GateOut Time')),
                        'value' => function ($model) {
                            return Yii::$app->controls->view_datetime($model->departure_time);
                        }
                    ],
                    ['attribute' => 'out_remarks', 'label' => (Yii::t('app', 'GateOut Remarks')),],
                    [
                        'attribute' => 'is_virtual_location',
                        'label' => Yii::t('app', 'Location Type'),
                        'value' => function ($model) {
                            $labels = [
                                1 => 'conversion_vendor',
                                2 => 'virtual_plant',
                            ];
                            return $labels[$model->is_virtual_location] ?? '';
                        },
                    ],
                ];
                $cnt = 0;
                $ctnDep = 0;
                $allowedPlants = explode(',', Yii::$app->session->get('Plant'));
                $allowedBmcs = explode(',', Yii::$app->session->get('BMC'));
                $userType = Yii::$app->session->get('UserType');
                $grid_option = [
                    'id' => 'trip-detail-list',
                    'attributes' => $attribute,
                    'active_column' => FALSE,
                    'default_sorting' => FALSE,
                    'actions' => [
                        'gate-in' => function ($url, $model, $key) use (&$is_button_visible, &$cnt, &$allowedPlants, &$allowedBmcs, &$userType) {
                            $type = strtolower($model->source_org_type);
                            $RLS = ($type == 'plant' && $userType == 4 && in_array($model->source_org_code, $allowedPlants)) ||
                                    ($type == 'bmc' && $userType == 6 && in_array($model->source_org_code, $allowedBmcs)) ||
                                    ($type == 'party' && (($model->is_virtual_location == 1 && in_array($model->destination_code, $allowedPlants)) || ($model->is_virtual_location != 1 && in_array($userType, [3, 4]))));
                            if ($cnt == 0) {
                                $cnt++;
                                return '';
                            }
                            $class = 'link-disable';
                            if ($is_button_visible && empty($model->arrival_time) && $RLS) {
                                $is_button_visible = false;
                                $class = '';
                            }
                            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Gate In', 'class' => 'gate-in-btn ' . $class, 'data-vehicle_trip_detail_code' => $model->vehicle_trip_detail_code, 'data-flag' => 'gate-in',];
                            return Html::a('<i class="fa fa-sign-in-alt"></i>', '#', $options);
                        },
                        'gate-out' => function ($url, $model) use (&$is_button_visible, &$ctnDep, &$allowedPlants, &$allowedBmcs, &$userType) {
                            $type = strtolower($model->source_org_type);
                            $RLS = ($type == 'plant' && $userType == 4 && in_array($model->source_org_code, $allowedPlants)) ||
                                    ($type == 'bmc' && $userType == 6 && in_array($model->source_org_code, $allowedBmcs)) ||
                                    ($type == 'party' && (($model->is_virtual_location == 1 && in_array($model->destination_code, $allowedPlants)) || ($model->is_virtual_location != 1 && in_array($userType, [3, 4]))));
                            if ($model->is_last_destination == 1) {
                                return '';
                            }
                            $class = 'link-disable';
                            if ($ctnDep == 0) {
                                if (empty($model->departure_time) && $RLS) {
                                    $class = '';
                                }
                                $ctnDep++;
                            }
                            if ($is_button_visible && empty($model->departure_time)) {
                                if (!empty($model->challan_no) && $RLS) {
                                    $class = '';
                                }
                                $is_button_visible = false;
                            }
                            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Gate Out', 'class' => 'gate-out-btn ' . $class, 'data-vehicle_trip_detail_code' => $model->vehicle_trip_detail_code, 'data-flag' => 'gate-out',];
                            return Html::a('<i class="fa fa-sign-out-alt"></i>', '#', $options);
                        },
                    ],
                ];
                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
                ?>
            </div>
        </div>
    </div>

    <div id="TripDetail"></div>
    <?php
    $script = "
    $(document).ready(function(){
        $(document).on('click', '.gate-in-btn, .gate-out-btn', function(e){
            e.preventDefault();

            var vehicle_trip_detail_code = $(this).data('vehicle_trip_detail_code');
            var actionType = $(this).hasClass('gate-in-btn') ? 'gate-in' : 'gate-out';
            var actionUrl = '" . Url::to(['/tankermovement/tbl-vehicle-trip/gate-process']) . "';

            $.ajax({
                type: 'get',
                url: actionUrl,
                data: { 
                    'vehicle_trip_detail_code': vehicle_trip_detail_code,
                    'actionType': actionType 
                },
                success: function(data) {  
                    $('#TripDetail').html(data);
                    $('#TripDetailModal').modal('toggle');  
                },    
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        });
    });
";
    $this->registerJs($script, View::POS_END, 'vehicle-trip-detail');
    ?>
