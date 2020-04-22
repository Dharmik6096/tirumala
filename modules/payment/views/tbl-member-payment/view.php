<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;

$this->title = Yii::$app->label->title('view', 'Member Payment');
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
                                'attribute' => 'dcs_code',
                                'label' => Yii::t('app', 'DCS Code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'dcs_code',
                                'label' => Yii::t('app', 'Code Ex.'),
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'dcs_code',
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'member_count',
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
                                'attribute' => 'qty',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'total_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'total_addition',
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
                                'attribute' => 'net_payable',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'hold_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'additional_pay',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'final_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'payment_status',
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
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Member Payment Details') ?></h5></div>
        <div class="form-grid">
            <?php
            $attribute = [
                    ['attribute' => 'union_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
                    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
                    ['attribute' => 'plant_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
                    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
                    ['attribute' => 'bmc_code', 'visible' => false,
                    'label' => Yii::t('app', 'BMC Code'),
                    'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
                    ['attribute' => 'bmc_code', 'visible' => false,
                    'label' => Yii::t('app', 'BMC Name'),
                    'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    }, 'vAlign' => 'middle', 'filter' => false],
                    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'visible' => false],
                    ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'visible' => false, 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                    }],
                    ['attribute' => 'dcs_name', 'visible' => false, 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    }, 'label' => Yii::t('app', 'DCS')],
                    ['attribute' => 'member_code', 'value' => function($model) {
                        return substr($model->member_code, -4);
                    }, 'label' => Yii::t('app', 'Member Code')],
                    ['attribute' => 'member_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
                    }],
                    ['attribute' => 'payment_cycle_code', 'value' => function($model) {
                        return Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime);
                    }, 'filter' => false, 'format' => 'raw'],
                    [
                    'attribute' => 'payment_date',
                    'filterType' => GridView::FILTER_DATE,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['format' => 'dd-mm-yyyy',
                            'autoclose' => true]
                    ],
                    'value' => function($model) {
                        return Yii::$app->controls->view_date($model->payment_date);
                    }],
                    ['attribute' => 'kg_fat'],
                    ['attribute' => 'kg_snf'],
                    ['attribute' => 'qty'],
                    ['attribute' => 'avg_fat', 'visible' => false],
                    ['attribute' => 'avg_snf', 'visible' => false],
                    ['attribute' => 'avg_rate', 'visible' => false],
                    ['attribute' => 'bank_name', 'visible' => false],
                    ['attribute' => 'branch_name', 'visible' => false],
                    ['attribute' => 'ifsc', 'visible' => false],
                    ['attribute' => 'bank_account_no', 'visible' => false],
                    ['attribute' => 'total_amount'],
                    ['attribute' => 'total_addition'],
                    ['attribute' => 'total_deduction'],
                    ['attribute' => 'previous_hold'],
                    ['attribute' => 'previous_due'],
                    ['attribute' => 'net_payable'],
                    ['attribute' => 'hold_amount'],
                    ['attribute' => 'additional_pay'],
                    ['attribute' => 'final_amount'],
                    ['attribute' => 'adjust_remark'],
                    ['attribute' => 'payment_status'],
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