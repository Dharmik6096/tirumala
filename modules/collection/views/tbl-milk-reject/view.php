<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblPurchaseRate */

$this->title = Yii::$app->label->title('view', 'Milk Reject');
//$this->title = $model->purchase_rate_code;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Purchase Rates'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
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
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'mcc_plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'milk_reject_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'date_time_of_collection',
                                'value' => Yii::$app->controls->view_date($model->date_time_of_collection),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ]
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'source_org_type',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'source_org_code',
                                'value' => $model->source_org_type == 'bmc' ? Yii::$app->general->getforeignkey($model->sourceBmcCode, 'bmc_name') : Yii::$app->general->getforeignkey($model->sourcePlantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'dest_org_type',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'dest_org_code',
                                'value' => $model->source_org_type == 'bmc' ? Yii::$app->general->getCustomer($model, $model->dest_org_type) : Yii::$app->general->getforeignkey($model->destBmcCode, 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'milk_type_code',
                                'value' => Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name'),
                                'valueColOptions' => ['style' => 'width:30%']
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
                                'attribute' => 'no_of_can',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'qty',
                                'valueColOptions' => ['style' => 'width:30%'],
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
                                'attribute' => 'rejection_reason_code',
                                'value' => Yii::$app->general->getforeignkey($model->rejectReason, 'rejection_reason'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'return_type',
                                'value' => isset($model->return_type) ? Yii::$app->dropdown->getRecords('return_type')['data'][$model->return_type] : 'N/A',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'sample_no',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'action_taken',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'remarks',
                                'valueColOptions' => ['style' => 'width:80%'],
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