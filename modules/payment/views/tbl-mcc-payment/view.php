<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;

$this->title = Yii::$app->label->title('view', 'MCC Payment');
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
                                'attribute' => 'bank_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'branch_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'ifsc',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bank_account_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'payment_cycle_code',
                                'value' => Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'payment_date',
                                'value' => Yii::$app->controls->view_date($model->payment_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'kg_fat',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'kg_snf',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
     
                      [
                        'columns' => [
                            [
                                'attribute' => 'minimum_qty',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'minimum_qty_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'total_qty',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'total_qty_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'addition',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'deduction',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'previous_hold',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'previous_due',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'hold_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'adjust_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'final_pay',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'adjust_remark',
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
                                'attribute' => 'status',
                                'value' => $model->status == 'sent' ? 'disbursed' : $model->status,
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
                    'deleteOptions' => [// your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"></h5></div> -->
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Bill Head Detail') ?></h4>
            </div>
            <div class="form-grid">
                <?php
                $attribute = [
                    ['attribute' => 'mcc_bill_head_code', 'value' => function($model) {
                            return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
                        }
                    ],
                    ['attribute' => 'bill_head_type',
                        'value' => function($model) {
                            return isset($model->billHeadCode->bill_head_type) ? Yii::$app->dropdown->getRecords('bill_head_type')['data'][$model->billHeadCode->bill_head_type] : 'N/A';
                        },],
                    ['attribute' => 'amount'],
                    ['attribute' => 'milk_type_code', 'label' => Yii::t('app', 'Milk Type'), 'value' => function($model) {
                            return Yii::$app->general->getmultiforeignkey($model->billHeadCode, ['milkTypeCode'], 'animal_type_name');
                        }, 'vAlign' => 'middle', 'filter' => false],
                ];
                $grid_option = [
                    'id' => 'bill-head-detail-list',
                    'attributes' => $attribute,
                    'active_column' => FALSE,
                ];
                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
                ?>
            </div>
        </div> 
    </div>
</div>