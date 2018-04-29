<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Milk Dispatch');
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
                            'label' => 'Union',
                            'value' => !empty($model->dcsCode) ? $model->dcsCode->unionCode->union_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'dcs_code',
                            'value' => !empty($model->dcsCode) ? $model->dcsCode->dcs_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'milk_dispatch_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'milk_type_code',
                            'value' => !empty($model->milkTypeCode) ? $model->milkTypeCode->animal_type_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
//                        [
//                            'attribute' => 'dcs_code',
//                            'value' => !empty($model->dcsCode) ? $model->dcsCode->dcs_name : '',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
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
                            'attribute' => 'water',
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
                            'attribute' => 'shift',
                            'value' => !empty($model->shiftCode) ? $model->shiftCode->shift : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'date_time_of_collection',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
//                        [
//                            'attribute' => 'date_time_of_recieve',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
                        [
                            'attribute' => 'village_code',
                            'value' => !empty($model->villageCode) ? $model->villageCode->village_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'sample_no',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                
//                [
//                    'columns' => [
//                        [
//                            'attribute' => 'type_of_data_receive',
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                        
//                    ],
//                ],
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