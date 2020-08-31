<?php

use kartik\detail\DetailView;
use kartik\grid\GridView;
?>
<div class="modal modal-default fade" id="PaymentDetailModal" role="dialog">
    <div class="modal-dialog popup-100-60">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Payment Detail of ') . $model->routeCode->route_name ?></h4>
            </div>
            <div class="panel panel-default panel-main">
                <div class="panel-heading"><?= $this->title ?></div>
                <div class="panel-body">
                    <div class="popup-header bg_white col-sm-12">
                        <div id="maincontent">
                            <div class="table-responsive">
                                <?php
                                $attributes = [
                                    [
                                        'columns' => [
                                            [
                                                'attribute' => 'transporter_type',
                                                'value' => isset($model->transporter_type) ? Yii::$app->dropdown->getRecords('transporter_type')['data'][$model->transporter_type] : 'N/A',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'transporter_code',
                                                'value' => Yii::$app->general->getforeignkey($model->transporterCode, 'transporter_name') . '(' . Yii::$app->general->getforeignkey($model->transporterCode, 'vendor_code') . ')',
                                                'valueColOptions' => ['style' => 'width:30%']
                                            ],
                                        ],
                                    ],
                                    [
                                        'columns' => [
                                            [
                                                'attribute' => 'mcc_plant_code',
                                                'label' => Yii::t('app', 'MCC Code'),
                                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'sloc_code'),
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'mcc_plant_code',
                                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                                'valueColOptions' => ['style' => 'width:30%']
                                            ],
                                        ],
                                    ],
                                    [
                                        'columns' => [
                                            [
                                                'attribute' => 'route_code',
                                                'label' => Yii::t('app', 'Route Code'),
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'route_code',
                                                'value' => Yii::$app->general->getforeignkey($model->routeCode, 'route_name'),
                                                'valueColOptions' => ['style' => 'width:30%']
                                            ],
                                        ],
                                    ],
                                    [
                                        'columns' => [
                                            [
                                                'attribute' => 'billing_type_code',
                                                'value' => Yii::$app->general->getforeignkey($model->billingTypeCode, 'billing_type') . ($model->billing_type_code == 2 ? ($model->is_day_wise == 1 ? '(Day Wise)' : '(Month Wise)') : ''),
                                                'valueColOptions' => ['style' => 'width:15%']
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
                                                'attribute' => 'total_kms',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'total_vts_kms',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'total_least_kms',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                        ],
                                    ],
                                    [
                                        'columns' => [
                                            [
                                                'attribute' => 'bill_no',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'total_qty',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'total_rejected_qty',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                        ],
                                    ],
                                    [
                                        'columns' => [
                                            [
                                                'attribute' => 'from_date',
                                                'label' => Yii::t('app', 'Period'),
                                                'value' => Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date),
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'qty_amount',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'total_amount',
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
                                                'attribute' => 'total_penalty_amount',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                        ],
                                    ],
                                    [
                                        'columns' => [
                                            [
                                                'attribute' => 'total_rejected_amount',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'net_amount',
                                                'valueColOptions' => ['style' => 'width:15%']
                                            ],
                                            [
                                                'attribute' => 'final_amount',
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
                        <div id="gridcontentvehicle" class='hide-grid-settings'>
                            <h5 class="panel-heading"><?= Yii::t('app', 'Date wise Payment Details') ?></h5>
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
                                [ 'attribute' => 'parsing_no', 'label' => 'Vehicle', 'filter' => false],
                                [ 'attribute' => 'morning_qty', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'evening_qty', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'qty', 'label' => 'Qty', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'qty_amount', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'morning_rejected_qty', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'evening_rejected_qty', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'rejected_qty', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'primary_tpt_cost', 'filter' => false],
                                [ 'attribute' => 'incentive_value', 'filter' => false],
                                [ 'attribute' => 'e_basic_price', 'filter' => false],
                                [ 'attribute' => 'm_basic_price', 'filter' => false],
                                [ 'attribute' => 'rejected_amount', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'morning_late_minute', 'filter' => false],
                                [ 'attribute' => 'morning_applicable_penalty', 'filter' => false],
                                [ 'attribute' => 'evening_late_minute', 'filter' => false],
                                [ 'attribute' => 'evening_applicable_penalty', 'filter' => false],
                                [ 'attribute' => 'penalty_amount', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'morning_kms', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'evening_kms', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'total_kms', 'label' => 'Total(KM)', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'morning_vts_kms', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'evening_vts_kms', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'total_vts_kms', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'morning_least_kms', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'evening_least_kms', 'filter' => false, 'pageSummary' => true],
                                [ 'attribute' => 'total_least_kms', 'filter' => false, 'pageSummary' => true],
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
                        <div id="gridcontenthead" class='hide-grid-settings'>
                            <h5 class="panel-heading"><?= Yii::t('app', 'Payment Head Details') ?></h5>
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

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

