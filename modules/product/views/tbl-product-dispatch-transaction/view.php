<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblProductSale */

$this->title = Yii::$app->label->title('view', 'Product Dispatch Transaction');
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
                                'value' => Yii::$app->general->getforeignkey($model->mccCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
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
                                'label' => Yii::t('app', 'Code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'vendor_code',
                                'label' => Yii::t('app', 'Name'),
                                'value' => $model->getEntityName(), //isset($model->vendor_type) ? Yii::$app->general->getCustomer($model, $model->vendor_type) : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'dispatch_date',
                                'value' => Yii::$app->controls->view_date($model->dispatch_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'product_code',
                                'value' => Yii::$app->general->getforeignkey($model->productCode, 'product_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'uom',
                                'value' => $model->getUom($model->product_code), //isset($model->vendor_type) ? Yii::$app->general->getCustomer($model, $model->vendor_type) : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'rate',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'dispatch_qty',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'discount_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    
                        [
                        'columns' => [
                                [
                                'attribute' => 'amount',
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
                    'container' => ['id' => 'product-dispatch-txn'],
                ]);
                ?>
            </div>
        </div> 
    </div>
</div>
