<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Member Provisional Approval');
$approval_detail = $model->processApprovalCode;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">
        <div class="panel-heading">
            <?= Html::encode($this->title) ?>
        </div>
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
                            'attribute' => 'from_date',
                            'value' => Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date),
                            'valueColOptions' => ['style' => 'width:30%'],
                            'label' => Yii::t('app', 'Payment Cycle')
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
                            'attribute' => 'avg_fat',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'avg_snf',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                            [
                            'attribute' => 'avg_rate',
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
                            'attribute' => 'total_amount',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'total_deduction',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'final_amount',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'total_count',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'approval_status',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'customer_type',
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
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'mode' => 'view',
                'bordered' => true,
                'striped' => false,
                'responsive' => true,
                'hAlign' => 'left',
                'vAlign' => 'top',
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
        <div class="row">
            <div class="col-md-12 padding_10_0 theme-box mt10">
                <div class="col-sm-12 col-md-12">
                    <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Payment Transaction Approval Detail') ?></h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_approval_process_grid', [
                        'approvalDataProvider' => $approvalDataProvider,
                        'approval' => $approval
                    ])
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Payment Transaction Detail') ?></h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_transaction_grid', [
                        'dataProvider' => $dataProvider,
                        'transaction' => $transaction
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
