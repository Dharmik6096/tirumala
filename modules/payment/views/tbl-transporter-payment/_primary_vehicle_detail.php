<?php

use kartik\detail\DetailView;
use kartik\grid\GridView;
?>

<div id="maincontent">
    <div class="table-responsive">
        <?php
        $attributes = [
                [
                'columns' => [
                        [
                        'attribute' => 'bmc_code',
                        'label' => Yii::t('app', 'BMC Code'),
                        'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code'),
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
                        'attribute' => 'transporter_type',
                        'value' => isset($model->transporter_type) ? Yii::$app->dropdown->getRecords('transporter_type')['data'][$model->transporter_type] : 'N/A',
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                        [
                        'attribute' => 'transporter_code',
                        'value' => $model->transporter_name . '(' . Yii::$app->general->getforeignkey($model->transporterCode, 'vendor_code') . ')',
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'billing_method',
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                        [
                        'attribute' => 'from_date',
                        'label' => Yii::t('app', 'Agreement Period'),
                        'value' => !empty($model->transporterCode->agreement_from_date) ? Yii::$app->controls->view_date($model->transporterCode->agreement_from_date) . ' to ' . Yii::$app->controls->view_date($model->transporterCode->agreement_to_date) : 'N/A',
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'bill_no',
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                        [
                        'attribute' => 'from_date',
                        'label' => Yii::t('app', 'Period'),
                        'value' => Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date),
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'parsing_no',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'route_code',
                        'label' => Yii::t('app', 'Route Code'),
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'route_code',
                        'value' => Yii::$app->general->getforeignkey($model->routeCode, 'route_name'),
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'no_of_days',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'total_kms',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'total_qty',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'vehicle_average',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'fuel_consumption',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'fuel_rate',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'avg_rate',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'fixed_rent',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'qty_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'fixed_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'total_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'total_addition',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'total_deduction',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'net_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'adjust_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'tds_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'final_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'adjust_remark',
                        'valueColOptions' => ['style' => 'width:60%']
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
<div id="gridcontentvehicle" class='hide-grid-settings padding_top_10'>
    <h4 class="theme-box-heading padding_top_10"><?= Yii::t('app', 'Date wise Payment Details') ?></h4>
    <?php
    $attribute = [
            ['attribute' => 'dispatch_date',
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->dispatch_date);
            }, 'filter' => false],
            ['attribute' => 'morning_qty', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'evening_qty', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'qty', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'extra_qty', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'avg_rate', 'filter' => false],
            ['attribute' => 'morning_kms', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'evening_kms', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'total_kms', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'extra_kms', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'km_rate', 'filter' => false],
            ['attribute' => 'fuel_consumption', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'fuel_rate', 'filter' => false],
            ['attribute' => 'amount', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'fixed_amount', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'total_amount', 'filter' => false, 'pageSummary' => true],
    ];


    $grid_option = [
        'id' => 'tpt-payment-detail',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'showPageSummary' => true,
    ];
    Yii::$app->grid->bind($vehicleDetail, $searchModel, $grid_option);
    ?>
</div>
<div id="gridcontenthead" class='hide-grid-settings padding_top_10'>
    <h4 class="theme-box-heading"><?= Yii::t('app', 'Payment Head Details') ?></h4>
    <?php
    $attribute = [
            ['attribute' => 'transporter_payment_head_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->paymentHeadCode, 'transporter_payment_head');
            }, 'filter' => false],
            ['attribute' => 'type', 'value' => function($model) {
                return isset($model->type) ? Yii::$app->dropdown->getRecords('calc_type')['data'][$model->type] : '';
            }, 'filter' => false],
            ['attribute' => 'amount', 'filter' => false],
    ];


    $grid_option = [
        'id' => 'tpt-payment-head-detail',
        'attributes' => $attribute,
        'active_column' => FALSE,
    ];
    Yii::$app->grid->bind($headDetail, $searchModelHead, $grid_option);
    ?>
</div>