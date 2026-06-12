<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblProductSale */

$this->title = Yii::$app->label->title('view', 'Product Receipt');
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
                                'attribute' => 'bmc_code',
                                'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'grn_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'grn_date',
                                'format' => 'html',
                                'value' => !empty($model->grn_date) ? date('d-m-Y', strtotime($model->grn_date)) : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'challan_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'challan_date',
                                'format' => 'html',
                                'value' => !empty($model->challan_date) ? date('d-m-Y', strtotime($model->challan_date)) : '',
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
                                'attribute' => 'challan_verified',
                                'value' => ($model->challan_verified == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'vendor_type',
                                'value' => Yii::$app->general->getforeignkey($model->customerType, 'customer_desc'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'vendor_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'vendor_code',
                                'label' => Yii::t('app', 'Name'),
                                'value' => isset($model->vendor_type) ? Yii::$app->general->getCustomer($model, $model->vendor_type) : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'description',
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
        <div class="col-sm-12 col-md-12 margin-bottom-10 margin-top-10 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Product Receipt Transactions') ?></h4>
        </div>
        <div class="form-grid">
            <?php
            $attribute = [
                    ['attribute' => 'product_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
                    }, 'vAlign' => 'middle'],
                    ['attribute' => 'requested_quantity', 'vAlign' => 'middle'],
                    ['attribute' => 'dispatched_quantity', 'vAlign' => 'middle'],
                    ['attribute' => 'received_quantity', 'vAlign' => 'middle'],
                    ['attribute' => 'rejected_quantity', 'vAlign' => 'middle'],
                    ['attribute' => 'rate', 'vAlign' => 'middle'],
                    ['attribute' => 'uom', 'value' => function($model) {
                        return Yii::$app->general->getmultiforeignkey($model->productCode, ['unitCode'], 'unit_name');
                    }, 'vAlign' => 'middle'],
                    ['attribute' => 'amount', 'vAlign' => 'middle'],
                    ['attribute' => 'discount', 'vAlign' => 'middle'],
                    ['attribute' => 'remark', 'vAlign' => 'middle'],
                    ['attribute' => 'product_requisition_code', 'vAlign' => 'middle', 'visible' => false],
                    ['attribute' => 'requisition_transaction_code', 'vAlign' => 'middle', 'visible' => false],
            ];

            $grid_option = [
                'id' => 'product-receipt-txn-list',
                'attributes' => $attribute,
                'active_column' => false,
            ];

            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
            ?>
        </div> 
    </div>
</div>
