<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Tanker Milk Lot Quality');
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
                            'attribute' => 'union_code',
                            'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'vehicle_code',
                            'value' => isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'arrival_datetime',
                            'valueColOptions' => ['style' => 'width:30%'],
                            'value' => Yii::$app->controls->view_datetime($model->arrival_datetime)
                        ]
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'lot_datetime',
                            'valueColOptions' => ['style' => 'width:30%'],
                            'value' => Yii::$app->controls->view_datetime($model->lot_datetime)
                        ],
                        [
                            'attribute' => 'lot_no',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'trip_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'chamber_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'acidity',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'mbrt',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
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
                        ]
                    ]
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
                        ]
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'density',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'protein',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'lactose',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'freezing_point',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'temp',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'status',
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'status_datetime',
                            'valueColOptions' => ['style' => 'width:30%'],
                            'value' => Yii::$app->controls->view_datetime($model->status_datetime)
                        ],
                        [
                            'attribute' => 'tested_by',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'verified_by',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'sample_datetime',
                            'value' => Yii::$app->controls->view_datetime($model->sample_datetime),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'record_status',
                            'value' => isset($model->record_status) ? Yii::$app->dropdown->getRecords('record_status')['data'][$model->record_status] : '',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ]
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
</div>