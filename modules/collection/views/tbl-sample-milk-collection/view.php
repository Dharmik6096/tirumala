<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Sample Milk Collection');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            $attributes = [
                    [
                    'columns' => [
                            [
                            'attribute' => 'sample_milk_collection_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'union_code',
                            'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:30%']
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
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'dcs_code',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'route_code',
                            'label' => Yii::t('app', 'Route Name'),
                            'value' => Yii::$app->general->getforeignkey($model->routeCode, 'route_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                            [
                            'attribute' => 'date_time_of_collection',
                            'value' => Yii::$app->controls->view_date($model->date_time_of_collection),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'shift_code',
                            'value' => Yii::$app->general->getforeignkey($model->shiftCode, 'shift'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'milk_type_code',
                            'value' => Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'milk_quality_type_code',
                            'value' => Yii::$app->general->getforeignkey($model->milkQualityCode, 'milk_quality_type_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'qty',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'fat',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'snf',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'clr',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'water',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'protein',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'density',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'lactose',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'qty_mode',
                            'value' => isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'converted_qty',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'converted_qty_mode',
                            'value' => isset($model->converted_qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->converted_qty_mode] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'rtpl',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'amount',
                            'format' => Yii::$app->general->CurrencyFormat(),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'purchase_rate_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'qlty_time',
                            'value' => Yii::$app->controls->view_datetime($model->qlty_time, 'php:d-m-Y H:i:s'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'qty_time',
                            'value' => Yii::$app->controls->view_datetime($model->qty_time, 'php:d-m-Y H:i:s'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'qlty_auto',
                            'value' => isset($model->qlty_auto) ? Yii::$app->dropdown->getRecords('is_quality_auto')['data'][$model->qlty_auto] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'qty_auto',
                            'value' => isset($model->qty_auto) ? Yii::$app->dropdown->getRecords('is_quality_auto')['data'][$model->qty_auto] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'milk_analyser_type_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'ws_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'source_of_milk',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ]
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'remarks',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'version_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'device_lat',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'device_long',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'originating_org_type',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'originating_type',
                            'value' => Yii::$app->general->getOriginatingType($model, 'originating_type'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
            ];
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
</div>