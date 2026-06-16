<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblProductSale */

$this->title = Yii::$app->label->title('view', 'Product Sales');
//$this->params['menu'][] = Yii::$app->controls->update($model->product_sale_code);
//$this->title = $model->product_sale_code;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Sales'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
<!--    <p>
    <?php // Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->product_sale_code], ['class' => 'btn btn-primary']) ?>
    <?php
    //
//    Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->product_sale_code], [
//        'class' => 'btn btn-danger',
//        'data' => [
//            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
//            'method' => 'post',
//        ],
//    ])
    ?>
    </p>-->
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'union_code',
                                'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
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
                                'attribute' => 'customer_type',
                                'value' => Yii::$app->general->getforeignkey($model->customerType, 'customer_desc'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'customer_code',
                                'value' => isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type) : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'invoice_date',
                                'format' => 'html',
                                'value' => date('d-m-Y', strtotime($model->invoice_date)),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'payment_mode',
                                'value' => isset($model->payment_mode) ? Yii::$app->dropdown->getRecords('payment_mode')['data'][$model->payment_mode] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'amount',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'other_amount',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'discount',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'paid_amount',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_installment',
                                'format' => 'html',
                                'value' => $model->is_installment ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'no_of_installment',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'amount_due',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'Channel'),
                                'value' => Yii::$app->general->getmultiforeignkey($model->bmcCode, ['channelMaster'], 'channel_desc'),
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'deduction_start_date',
                                'format' => 'html',
                                'value' => !empty($model->deduction_start_date) ? date('d-m-Y', strtotime($model->deduction_start_date)) : date('d-m-Y', strtotime($model->invoice_date)),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                    'attribute' => 'voucher_code',
                                    'label' => Yii::t('app', 'Voucher Code'),
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
        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"></h5></div> -->
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Product Details</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('../../../payment/views/tbl-product-sale-details/_produtc_list', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div> 
        </div>
    </div>
</div>
