<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblPurchaseRate */

$this->title = Yii::$app->label->title('view', 'Milk Tranfer');
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
                $source_rel = Yii::$app->general->getDestRelation($model->source_type);
                $source_att = strtolower($model->source_type) == 'bmc' ? 'bmc_name' : (strtolower($model->source_type) == 'vendor' ? 'customer_name' : 'name');
                $dest_rel = Yii::$app->general->getDestRelation($model->destination_type);
                $dest_att = strtolower($model->destination_type) == 'bmc' ? 'bmc_name' : (strtolower($model->destination_type) == 'vendor' ? 'customer_name' : 'name');

                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'union_code',
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'source_code',
                                'label' => (Yii::t('app', 'Source Ref. Code')),
                                'value' => !empty($source_rel) ? Yii::$app->general->getforeignkey($model->{$source_rel . 'Source'}, 'ref_code') : $model->source_code,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'destination_code',
                                'label' => (Yii::t('app', 'Dest Ref. Code')),
                                'value' => !empty($dest_rel) ? Yii::$app->general->getforeignkey($model->{$dest_rel . 'Dest'}, 'ref_code') : $model->destination_code,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'source_code',
                                'label' => (Yii::t('app', 'Source Code')),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'destination_code',
                                'label' => (Yii::t('app', 'Dest Code')),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'source_code',
                                'label' => (Yii::t('app', 'Source')),
                                'value' => !empty($source_rel) ? Yii::$app->general->getforeignkey($model->{$source_rel . 'Source'}, $source_att) . '-' . strtoupper($model->source_type) : $model->source_type,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'destination_code',
                                'label' => (Yii::t('app', 'Destination')),
                                'value' => !empty($dest_rel) ? Yii::$app->general->getforeignkey($model->{$dest_rel . 'Dest'}, $dest_att) . '-' . strtoupper($model->destination_type) : $model->destination_type,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'from_date',
                                'value' => Yii::$app->controls->view_date($model->from_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'from_shift',
                                'value' => Yii::$app->general->getforeignkey($model->fromShiftCode, 'shift'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'to_date',
                                'value' => Yii::$app->controls->view_date($model->to_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'to_shift',
                                'value' => Yii::$app->general->getforeignkey($model->toShiftCode, 'shift'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'transaction_datetime',
                                'value' => Yii::$app->controls->view_date($model->transaction_datetime),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'shift_code',
                                'value' => Yii::$app->general->getforeignkey($model->shiftCode, 'shift'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'transfer_type',
                                'value' => Yii::$app->dropdown->getRecords('transfer_type')['data'][$model->transfer_type],
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'qty',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'fat',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'snf',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ]
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'clr',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'temp',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'water',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'protein',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'density',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'lactose',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'adt_param',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'adt_value',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'salt',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'freezing_point',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'ph_value',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'other_reading',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'remarks',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'is_rechilling',
                                'value' => ($model->is_rechilling == 0) ? 'No' : 'Yes',
                                'valueColOptions' => ['style' => 'width:30%'],
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
    </div>
</div>