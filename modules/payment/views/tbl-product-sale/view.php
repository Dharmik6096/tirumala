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
                                'attribute' => 'member_code',
                                'value' => Yii::$app->general->getforeignkey($model->memberCode, 'member_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'sale_date_time',
                                'format' => 'html',
                                'value' => date('d-m-Y', strtotime($model->sale_date_time)),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'sale_mode',
                                'value' => isset($model->sale_mode) ? Yii::$app->dropdown->getRecords('payment_mode')['data'][$model->sale_mode] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'amount',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'other_amount',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'discount',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'paid_amount',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'is_installment',
                                'format' => 'html',
                                'value' => $model->is_installment ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'no_of_installment',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'amount_due',
                                'format' => Yii::$app->general->CurrencyFormat(),
                                'valueColOptions' => ['style' => 'width:30%'],
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
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Product Details</h5></div>
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
