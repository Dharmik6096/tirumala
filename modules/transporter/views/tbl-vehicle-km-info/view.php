<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Vehicle KM Information');
$this->params['menu'][] = Yii::$app->controls->update($model->km_info_code);
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
                            'attribute' => 'vehicle_code',
                            'value' => $model->vehicle->parsing_no.'/'.$model->vehicle->vehicleType->vehicle_type_name,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'route_code',
                            'value' => isset($model->routeCode) ? $model->routeCode->route_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'transporter_code',
                            'value' => $model->transporterCode->transporter_name,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'wef_date',
                            'value' => Yii::$app->controls->view_date($model->wef_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                
                
                [
                    'columns' => [
                        [
                            'attribute' => 'morning_kms',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'evening_kms',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'extra_kms',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'total_kms',
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
