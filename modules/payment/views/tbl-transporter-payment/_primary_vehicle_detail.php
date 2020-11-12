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
                        'attribute' => 'from_date',
                        'label' => Yii::t('app', 'Period'),
                        'value' => Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date),
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                    [
                        'attribute' => 'bill_no',
                        'valueColOptions' => ['style' => 'width:30%']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'billing_method',
                        'valueColOptions' => ['style' => 'width:80%']
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
                        'attribute' => 'fixed_rent',
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
                        'attribute' => 'total_kms',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                    [
                        'attribute' => 'fuel_consumption',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                    [
                        'attribute' => 'vehicle_average',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'fuel_rate',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                    [
                        'attribute' => 'total_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                    [
                        'attribute' => 'fixed_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'total_addition',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                    [
                        'attribute' => 'total_deduction',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                    [
                        'attribute' => 'net_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'adjust_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                    [
                        'attribute' => 'final_amount',
                        'valueColOptions' => ['style' => 'width:15%']
                    ],
                    [
                        'attribute' => 'adjust_remark',
                        'valueColOptions' => ['style' => 'width:15%']
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
<div id="gridcontentvehicle" class='hide-grid-settings padding_top_10'>
        <h4 class="theme-box-heading padding_top_10"><?= Yii::t('app', 'Date wise Payment Details') ?></h4>
    <?php
    $attribute = [
        [ 'attribute' => 'dispatch_date',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
        return Yii::$app->controls->view_date($model->dispatch_date);
    }, 'filter' => false],
        [ 'attribute' => 'morning_qty', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'evening_qty', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'qty', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'rec_kg_fat', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'rec_kg_snf', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'morning_kms', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'evening_kms', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'total_kms', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'fuel_consumption', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'fuel_rate', 'filter' => false],
        [ 'attribute' => 'amount', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'fixed_amount', 'filter' => false, 'pageSummary' => true],
        [ 'attribute' => 'total_amount', 'filter' => false, 'pageSummary' => true],
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
        [ 'attribute' => 'transporter_payment_head_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->paymentHeadCode, 'transporter_payment_head');
            }, 'filter' => false],
        ['attribute' => 'type', 'value' => function($model) {
                return isset($model->type) ? Yii::$app->dropdown->getRecords('calc_type')['data'][$model->type] : '';
            }, 'filter' => false],
        [ 'attribute' => 'amount', 'filter' => false],
    ];


    $grid_option = [
        'id' => 'tpt-payment-head-detail',
        'attributes' => $attribute,
        'active_column' => FALSE,
    ];
    Yii::$app->grid->bind($headDetail, $searchModelHead, $grid_option);
    ?>
</div>