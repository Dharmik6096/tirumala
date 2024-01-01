<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Milk Receipt');
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
                $reldest = Yii::$app->general->getDestRelation(strtolower($model->receipt_at));
                $attdest = strtolower($model->receipt_at) == 'bmc' ? 'bmc_name' : (strtolower($model->receipt_at) == 'vendor' ? 'customer_name' : (strtolower($model->receipt_at) == 'party' ? 'party_name' : 'name'));

                $relsource = Yii::$app->general->getDestRelation(strtolower($model->dispatch_from));
                $attsource = strtolower($model->dispatch_from) == 'bmc' ? 'bmc_name' : (strtolower($model->dispatch_from) == 'vendor' ? 'customer_name' : (strtolower($model->dispatch_from) == 'party' ? 'party_name' : 'name'));
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'union_code',
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'mcc_plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'BMC Code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'BMC Name'),
                                'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'vehicle_entry_date',
                                'value' => Yii::$app->controls->view_date($model->vehicle_entry_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'receipt_at',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'receipt_at_code',
                                'value' => !empty($reldest) ? Yii::$app->general->getforeignkey($model->{$reldest . 'Dest'}, $attdest) . '-' . strtoupper($model->receipt_at_code) : $model->receipt_at_code,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'dispatch_from',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'dispatch_from_code',
                                'value' => !empty($relsource) ? Yii::$app->general->getforeignkey($model->{$relsource . 'Source'}, $attsource) . '-' . strtoupper($model->dispatch_from_code) : $model->dispatch_from_code,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'trip_code',
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
                                'attribute' => 'qty',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'receipt_at',
                                'value' => Yii::$app->general->getStaticValue($model->receipt_at, 'receipt_at'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'arrival_time',
                                'value' => Yii::$app->controls->view_time($model->arrival_time),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'tare_weight_time',
                                'value' => Yii::$app->controls->view_time($model->tare_weight_time),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'gross_weight',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'tare_weight',
                                'valueColOptions' => ['style' => 'width:30%']
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
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Milk Receipt Transactions Detail') ?></h5></div>
        <div class="form-grid">
            <?=
            $this->render('_transaction_detail', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
            ?>
        </div>
    </div> 
</div>