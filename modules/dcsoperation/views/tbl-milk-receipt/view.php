<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMilkReceipt */

$this->title = Yii::t('app', Yii::$app->label->title('view', 'Milk Receipt'));
?>
<div class="tbl-milk-receipt-view">

    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="panel-subheading padding-0">
                <div class="table-responsive">
                    <?php
                    $attributes = [

                        [
                            'columns' => [
                                [
                                    'attribute' => 'milk_receipt_code', 'valueColOptions' => ['style' => 'width:30%']
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
                                    'attribute' => 'challan_no', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'from_date',
                                    'format' => 'html',
                                    'value' => Yii::$app->controls->view_date($model->from_date), 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'from_shift', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'to_date',
                                    'format' => 'html',
                                    'value' => Yii::$app->controls->view_date($model->to_date), 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'to_shift', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'nos_of_can', 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'rate',
                                    'format' => Yii::$app->general->CurrencyFormat(),
                                    'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'rate_calculation_date_time', 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'receipt_acidity', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'receipt_clr', 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'receipt_density', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'receipt_fat', 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'receipt_freezing_point', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'receipt_lactose', 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'receipt_local_type', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'receipt_org_code', 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'receipt_protein', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'receipt_qty', 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'receipt_snf', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'receipt_temp', 'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'receipt_water', 'valueColOptions' => ['style' => 'width:30%']
                                ],
                                [
                                    'attribute' => 'dcs_code',
                                    'value' => $model->dcsCode->dcs_name,
                                    'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'milk_quality_type_code',
                                    'value' => $model->milkQualityTypeCode->milk_quality_type_name,
                                    'valueColOptions' => ['style' => 'width:30%'],
                                ],
                                [
                                    'attribute' => 'milk_type',
                                    'value' => $model->milkType->animal_type_name,
                                    'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'receipt_org_chilling_center',
                                    'label' => 'Receipt Org Chilling Center',
                                    'format' => 'html',
                                    'value' => $model->receiptOrgChillingCenter->name,
                                    'valueColOptions' => ['style' => 'width:30%'],
                                ],
                                [
                                    'attribute' => 'receipt_org_id',
                                    'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'sub_center_code',
                                    'value' => $model->subCenterCode->sub_center_name,
                                    'valueColOptions' => ['style' => 'width:30%'],
                                ],
                                [
                                    'attribute' => 'union_code',
                                    'value' => $model->unionCode->union_name,
                                    'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                        [
                            'columns' => [
                                [
                                    'attribute' => 'is_active',
                                    'label' => 'Status',
                                    'format' => 'html',
                                    'value' => GeneralFunctions::getRecordStatus($model->is_active),
                                    'valueColOptions' => ['style' => 'width:30%'],
                                ],
                            ],
                        ],
                    ];

                    echo DetailView::widget([
                        'model' => $model,
                        'attributes' => $attributes,
                        'mode' => 'view',
                        'deleteOptions' => [ // your ajax delete parameters
                            'params' => ['id' => 1000, 'kvdelete' => true],
                        ],
                        'container' => ['id' => 'kv-demo'],
                    ]);
                    ?>

                </div>
            </div>
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>            
</div>
