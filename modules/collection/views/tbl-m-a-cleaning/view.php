<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Cleaning');
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
                            'attribute' => 'id',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'BMCCode',
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'PPCode',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'dtdate',
                            'valueColOptions' => ['style' => 'width:30%'],
                            'value' => Yii::$app->controls->view_date($model->dtdate)
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'shift',
                            'value' => Yii::$app->general->getforeignkey($model->shiftCode, 'shift'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'cleaningdatetime',
                            'valueColOptions' => ['style' => 'width:30%'],
                            'value' => Yii::$app->controls->view_date($model->cleaningdatetime)
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'CleaningCycles',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'Measuring',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
//                  [
//                    'columns' => [
//                        [
//                            'attribute' => 'counter',
//                            'valueColOptions' => ['style' => 'width:99%']
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




