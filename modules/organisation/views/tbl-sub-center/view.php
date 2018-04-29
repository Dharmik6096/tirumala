<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'sub center');
$this->params['menu'][] = Yii::$app->controls->update($model->sub_center_code);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="table-responsive">

            <?php
            // DetailView Attributes Configuration
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'sub_center_code',
                            'valueColOptions' => ['style' => 'width:80%'],
                        ],
                    ],
                ],
                [
                    'columns' => [

                        [
                            'attribute' => 'sub_center_name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'local_name',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [

                        [
                            'attribute' => 'address',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'local_address',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'milk_type_code',
                            'value' => $model->milkType(),
                            'valueColOptions' => ['style' => 'width:30%'],
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'is_bmc',
                            'format' => 'html',
                            'value' => $model->isBmcValue(),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'destination_type',
                            'format' => 'html',
                            'value' => $model->destinationTypeValue(),
                            'valueColOptions' => ['style' => 'width:80%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'phone_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'pincode',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'state_code',
                            'label' => 'State',
                            'value' => isset($model->stateCode) ? $model->stateCode->state_name : '',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'district_code',
                            'label' => 'District',
                            'value' => isset($model->districtCode) ? $model->districtCode->district_name : '',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ]
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'sub_district_code',
                            'label' => 'Sub District',
                            'value' => isset($model->subDistrictCode) ? $model->subDistrictCode->sub_district_name : '',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'hamlet_code',
                            'label' => 'Hamlet',
                            'value' => $model->hamletCode->hamlet_name,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'village_code',
                            'label' => 'Village',
                            'value' => isset($model->villageCode) ? $model->villageCode->village_name : '',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'federation_code',
                            'value' => isset($model->dcsCode) ? $model->dcsCode->unionCode->federationCode->federation_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'union_code',
                            'value' => isset($model->dcsCode) ? $model->dcsCode->unionCode->union_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'dcs_code',
                            'value' => isset($model->dcsCode) ? $model->dcsCode->dcs_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'contact_person',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'contact_person_email',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'contact_person_mobile_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'route_code',
                            'value' => $model->routeCode->route_name,
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'bank_code',
                            'value' => $model->bankCode->bank_name,
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'branch_code',
                            'value' => $model->branchCode->branch_name,
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'bank_account_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'ifsc',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'upi_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'is_main_dcs',
                            'label' => 'Main DCS',
                            'format' => 'html',
                            'value' => ($model->is_main_dcs == 1) ? 'Yes' : 'No',
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
                            'value' => GeneralFunctions::getRecordStatus($model->is_active)
                        ],
                    ]
                ]
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