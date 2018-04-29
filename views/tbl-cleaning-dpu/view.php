<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Dpu Cleaning');
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
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'Dtdate',
                            'value' => Yii::$app->controls->view_date($model->Dtdate),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'Shift',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'c1Date',
                            'value' => Yii::$app->controls->view_date($model->c1Date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'c1cycle',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'c1testing',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'c2Date',
                            'value' => Yii::$app->controls->view_date($model->c2Date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'c2cycle',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'c2testing',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'c3Date',
                            'value' => Yii::$app->controls->view_date($model->c3Date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'c3cycle',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'c3testing',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'c4Date',
                            'value' => Yii::$app->controls->view_date($model->c4Date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'c4cycle',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'c4testing',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'c5Date',
                            'value' => Yii::$app->controls->view_date($model->c5Date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'c5cycle',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'Counter',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'c5testing',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'CreatedDate',
                            'value' => Yii::$app->controls->view_date($model->CreatedDate),
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
                'deleteOptions' => [ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
    </div>
</div>
