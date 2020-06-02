<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblProductSale */

$this->title = Yii::$app->label->title('view', 'Product Dispatch');
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
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'plant_name',
                                'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'mcc_name',
                                'value' => Yii::$app->general->getforeignkey($model->mccCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
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
                                'attribute' => 'vendor_type',
                                'value' => Yii::$app->general->getforeignkey($model->customerType, 'customer_desc'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'vehicle_no',
                                'value' => Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no'),
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
                                'format' => 'html',
                                'value' => date('d-m-Y', strtotime($model->dispatch_date)),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'challan_verified',
                                'value' => isset($model->challan_verified) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->challan_verified] : '',
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
                    'container' => ['id' => 'product-dispatch'],
                ]);
                ?>
            </div>
        </div>
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Product Dispatch Transactions') ?></h5></div>
        <div class="form-grid">
            <?=
            $this->render('../../../product/views/tbl-product-dispatch-transaction/_list_grid', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div> 
    </div>
</div>
