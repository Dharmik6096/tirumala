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
                        'attribute' => 'billing_method',
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
                        'attribute' => 'transporter_type',
                        'value' => isset($model->transporter_type) ? Yii::$app->dropdown->getRecords('transporter_type')['data'][$model->transporter_type] : 'N/A',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'transporter_code',
                        'value' => $model->transporter_name . '(' . Yii::$app->general->getforeignkey($model->transporterCode, 'vendor_code') . ')',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'parsing_no',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'disp_qty',
                        'label' => Yii::t('app', 'Disp.QTY'),
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'rec_qty',
                        'label' => Yii::t('app', 'Rec.QTY'),
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
                        'attribute' => 'no_of_days',
                        'label' => Yii::t('app', 'No. Of Trip'),
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'total_kms',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'avg_rate',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'total_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'total_addition',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'total_deduction',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'net_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'adjust_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                        [
                        'attribute' => 'final_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
                [
                'columns' => [
                        [
                        'attribute' => 'adjust_remark',
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
<div id="gridcontentvehicle" class='hide-grid-settings padding_top_10'>
    <h4 class="theme-box-heading padding_top_10"><?= Yii::t('app', 'Date wise Payment Details') ?></h4>
    <?php
    $attribute = [
            ['attribute' => 'dispatch_datetime', 'label' => Yii::t('app', 'Disp.DATE'),
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->dispatch_datetime);
            }, 'filter' => false],
            ['attribute' => 'receipt_datetime', 'label' => Yii::t('app', 'Rec.DATE'),
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->receipt_datetime);
            }, 'filter' => false],
            ['attribute' => 'mchallan_no', 'label' => Yii::t('app', 'Challan No.'), 'filter' => false],
            ['attribute' => 'grn_no', 'label' => Yii::t('app', 'GRN No.'), 'filter' => false],
            ['attribute' => 'from_dest', 'value' => function($model) {
                $rel = Yii::$app->general->getDestRelation($model->from_type);
                $att = strtolower($model->from_type) == 'bmc' ? 'bmc_name' : (strtolower($model->from_type) == 'vendor' ? 'customer_name' : 'name');
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
            }, 'filter' => false],
            ['attribute' => 'to_dest', 'value' => function($model) {
                $rel = Yii::$app->general->getDestRelation($model->to_type);
                $att = strtolower($model->to_type) == 'bmc' ? 'bmc_name' : (strtolower($model->to_type) == 'vendor' ? 'customer_name' : 'name');
                ;
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
            }, 'filter' => false],
            ['attribute' => 'disp_qty', 'label' => Yii::t('app', 'Disp.Qty'), 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'rec_qty', 'label' => Yii::t('app', 'Rec.Qty'), 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'total_kms', 'label' => Yii::t('app', 'KM'), 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'km_rate', 'filter' => false],
            ['attribute' => 'amount', 'filter' => false, 'pageSummary' => true],
            ['attribute' => 'qty_amount', 'label' => Yii::t('app', 'Cost Per Ltr.'), 'filter' => false],
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