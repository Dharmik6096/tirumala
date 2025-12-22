<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Medicine Stock');
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
                                'attribute' => 'module_code',
                                'label' => Yii::t('app', 'User Code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'user_code',
                                'value' => Yii::$app->general->getforeignkey($model->userCode, 'name'),
                                'label' => Yii::t('app', 'Name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'medicine_id',
                                'label' => Yii::t('app', 'Medicine Name'),
                                'value' => Yii::$app->general->getforeignkey($model->medicineMasterCode, 'medicine_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'stock',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'batch_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'expire_date',
                                'value' => Yii::$app->controls->view_date($model->expire_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'rate',
                                'format' => Yii::$app->general->CurrencyFormat(),
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
                    'deleteOptions' => [
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Medicine Stock Transactions') ?></h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('_transaction_detail', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'isVisible' => true,
                    'txnEdit' => 'false',
                ]);
                ?>
            </div>
        </div>
    </div>
</div>