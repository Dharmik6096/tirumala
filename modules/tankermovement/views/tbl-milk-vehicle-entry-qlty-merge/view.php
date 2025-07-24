<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Tanker Milk Quality');
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
                            'attribute' => 'trip_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'chamber_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'fat',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'snf',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'clr',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'water',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'density',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'protein',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'lactose',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'freezing_point',
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
                            'attribute' => 'temp',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'acidity',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'is_qty_only',
                            'value' => $model->is_qty_only == 1 ? 'Yes' : 'No',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'is_pending_merge',
                            'value' => $model->is_pending_merge == 1 ? 'Yes' : 'No',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ]
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'tested_by',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'verified_by',
                            'valueColOptions' => ['style' => 'width:30%']
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
