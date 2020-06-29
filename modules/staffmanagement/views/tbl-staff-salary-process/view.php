<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Staff Salary Process');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model, ['index']); ?>
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
                                'attribute' => 'staff_member_code',
                                'value' => Yii::$app->general->getforeignkey($model->staffMemberCode, 'staff_member_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'Status',
                                'value' => empty($model->disbursement_date) ? 'Process' : 'Disburse',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'disbursement_date',
                                'value' => Yii::$app->controls->view_date($model->disbursement_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'month',
                                'value' => date('m/Y', strtotime($model->month)),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'effective_working_days',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'actual_value',
                                'value' => Yii::$app->general->decimalformat($model->actual_value),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'value',
                                'value' => Yii::$app->general->decimalformat($model->value),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'previous_hold',
                                'value' => Yii::$app->general->decimalformat($model->previous_hold),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'previous_due',
                                'value' => Yii::$app->general->decimalformat($model->previous_due),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'hold_amount',
                                'value' => Yii::$app->general->decimalformat($model->hold_amount),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'additional_pay',
                                'value' => Yii::$app->general->decimalformat($model->additional_pay),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'lwp',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bank_code',
                                'value' => Yii::$app->general->getforeignkey($model->bankCode, 'bank_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'branch_code',
                                'value' => Yii::$app->general->getforeignkey($model->branchCode, 'branch_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'designation_code',
                                'value' => Yii::$app->general->getforeignkey($model->designationCode, 'designation_name'),
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
                    'deleteOptions' => [// your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box view-subtitle">
                <div class="col-sm-12 col-md-12 margin-bottom-10 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading"><?= Yii::t('app', 'Head Wise Details') ?></h4>
                </div>
                <div class="">
                    <?=
                    $this->render('_detail_grid', [
                        'dataProvider' => $trDataProvider,
                        'searchModel' => $trModel,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>