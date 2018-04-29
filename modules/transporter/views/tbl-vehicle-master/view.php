<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use webvimark\modules\UserManagement\components\GhostHtml;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlant */

$this->title = Yii::$app->label->title('view', 'Vehicle');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Vehicle Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu'][] = Yii::$app->controls->update($model->vehicle_code);
//$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-user-circle-o"></i> Contact Details'), ['/organisation/tbl-plant/contact-details', 'id' => $model->plant_code], ['class' => 'btn btn-danger btn-block']);
?>
<div class="tbl-vehicle-master-view panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'union_code',
                                'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'transporter_code',
                                'value' => isset($model->transporter) ? $model->transporter->transporter_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'vehicle_type_code',
                                'value' => isset($model->vehicleType) ? $model->vehicleType->vehicle_type_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'parsing_no',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'fuel_type_code',
                                'value' => isset($model->fuelType) ? $model->fuelType->fuel_type : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'capacity_code',
                                'value' => isset($model->capacity) ? $model->capacity->value : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'registration_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'applicable_rto',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'wef_date',
                                'value' => Yii::$app->controls->view_date($model->wef_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'mapped_route',
                                'value' => isset($model->vehicleKmInfo) ? $model->vehicleKmInfo->routeCode->route_name:'',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'pollution_certificate',
                                'value' => ($model->pollution_certificate == 1) ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'expiry_date',
                                'value' => Yii::$app->controls->view_date($model->expiry_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'insurance',
                                'value' => ($model->insurance == 1) ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'rc_book_no',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'rent',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'average',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_active',
                                'label' => 'Status',
                                'format' => 'html',
                                'value' => GeneralFunctions::getRecordStatus($model->is_active),
                                'valueColOptions' => ['style' => 'width:80%'],
                            ],
                        ],
                    ],
                    [
                        'group' => true,
                        'label' => 'Driver Details',
                        'rowOptions' => ['class' => 'bg-default']
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'driver_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'driver_contact_no',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'driving_license_number',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'licence_expiry_date',
                                'value' => Yii::$app->controls->view_date($model->licence_expiry_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'group' => true,
                        'label' => 'Billing Details',
                        'rowOptions' => ['class' => 'bg-default']
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'billing_type',
                                'value' => isset($model->vehicleBillingType) ? $model->vehicleBillingType->billingType->billing_type:'',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'remarks',
                                'value' => isset($model->vehicleBillingType) ? $model->vehicleBillingType->remarks:'',
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
                    'deleteOptions' => [ // your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
