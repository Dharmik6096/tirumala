<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::t('app', $model->adjustment_type.' Details');
?>
<div class="tbl-purchase-rate-view">
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
                                    'attribute' => 'product_stock_adjustment_code',
                                    'valueColOptions' => ['style' => 'width:30%']
                                ],
                                    [
                                    'attribute' => 'transaction_date',
                                    'value' => Yii::$app->controls->view_date($model->transaction_date),
                                    'valueColOptions' => ['style' => 'width:30%']
                                ],
                            ],
                        ],
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
                                    'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                    'valueColOptions' => ['style' => 'width:30%']
                                ],
                                    [
                                    'attribute' => 'bmc_code',
                                    'value' => !empty($model->bmc_code) ? Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') : 'NA',
                                    'valueColOptions' => ['style' => 'width:30%']
                                ],
                            ],
                        ],
                            [
                            'columns' => [
                                    [
                                    'attribute' => 'dcs_code',
                                    'value' => !empty($model->dcs_code) ? Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') : 'NA',
                                    'valueColOptions' => ['style' => 'width:30%']
                                ],
                                    [
                                    'attribute' => 'adjustment_type',
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
            <div class="col-md-12 padding_10_0 theme-box view-subtitle">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                    <h4 class="theme-box-heading"><?php echo $model->adjustment_type; ?> Txn Details</h4>
                </div>
                <div class="form-grid">
                    <?=
                    $this->render('_list_grid', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>