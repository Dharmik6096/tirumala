<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Dpu Calibration');
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
                            'value' => !empty($model->dcsCode->dcs_name) ? $model->dcsCode->dcs_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'date',
                            'label' => 'Date Time',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'shift',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'milk_type_code',
                            'value' => !empty($model->milkTypeCode->milk_type_name)? : '',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'fat',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'snf',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'cycle',
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
                'deleteOptions' => [ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
    </div>
</div>
