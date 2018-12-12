<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'BMC Collection');
//$this->params['menu'][] = Yii::$app->controls->update($model->milk_collection_code);
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
                            'attribute' => 'bmc_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'bmc_name',
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'dcs_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'dcs_name',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'dcs_incharge_name',
                            'value' => !empty(Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society')) ? Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society')->firstname . ' ' . Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society')->lastname . ' ' . Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society')->surname : 'N/A',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'route_name',
                            'value' => Yii::$app->general->getmultiforeignkey($model->dcsCode, ['routeMapping'], 'route_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'date_time_of_collection',
                            'value' => Yii::$app->controls->view_date($model->date_time_of_collection),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'shift_code',
                            'value' => isset($model->shiftCode) ? $model->shiftCode->shift : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'milk_type_code',
                            'value' => isset($model->milkType) ? $model->milkType->animal_type_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'milk_quality_type_code',
                            'value' => isset($model->milkQualityType) ? $model->milkQualityType->milk_quality_type_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'fat',
//                            'value' => $model->transporterCode->transporter_name,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'snf',
//                            'value' => Yii::$app->controls->view_date($model->wef_date),
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
                            'attribute' => 'qty',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'qty_mode',
                            'value' => isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'converted_qty',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'amount',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'sample_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'type_of_data_receive',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'collection_type',
                            'value' => !empty($model->collection_type) ? Yii::$app->dropdown->getRecords('collection_type')['data'][$model->collection_type] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'transporter_code',
                            'value' => isset($model->transporter) ? $model->transporter->transporter_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'vehicle_code',
                            'value' => isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'remarks',
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
