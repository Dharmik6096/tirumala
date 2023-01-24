<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;

$this->title = Yii::$app->label->title('view', 'Vehicle Trip');
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
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'mcc_plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'BMC Code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'BMC Name'),
                                'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
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
                                'valueColOptions' => ['style' => 'width:80%']
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
                        'value' => function($model) {
                            return Yii::$app->controls->view_datetime($model->transaction_datetime);
                        }],
                        ['attribute' => 'source_org_code',],
                        ['attribute' => 'source_org_type', 'value' => function($model) {
                            $rel = Yii::$app->general->getDestRelation($model->source_org_type);
                            $att = strtolower($model->source_org_type) == 'bmc' ? 'bmc_name' : (strtolower($model->source_org_type) == 'vendor' ? 'customer_name' : 'name');
                            if (!empty($rel))
                                return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att) . '-' . strtoupper($model->source_org_type);
                        }, 'filter' => false],
                        ['attribute' => 'destination_code',],
                        ['attribute' => 'destination_type', 'value' => function($model) {
                            $rel = Yii::$app->general->getDestRelation($model->destination_type);
                            $att = strtolower($model->destination_type) == 'bmc' ? 'bmc_name' : (strtolower($model->destination_type) == 'vendor' ? 'customer_name' : 'name');
                            if (!empty($rel))
                                return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) . '-' . strtoupper($model->destination_type);
                        }, 'filter' => false],
                ];
                $grid_option = [
                    'id' => 'trip-detail-list',
                    'attributes' => $attribute,
                    'active_column' => FALSE,
                    'default_sorting' => FALSE
                ];
                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
                ?>
            </div> 
        </div>
    </div>