<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'Inventory Transfer');
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
                $fkey = strtolower($model->from_type);
                $tkey = strtolower($model->to_type);
                $from_key = strtolower($model->from_type) . 'FromCode';
                $from_field = $model->from_type == 'MCC' ? 'name' : $fkey . '_name';
                $to_key = strtolower($model->to_type) . 'ToCode';
                $to_field = $model->to_type == 'MCC' ? 'name' : $tkey . '_name';

                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'inventory_transfer_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'inventory_transfer_date',
                                'value' => Yii::$app->controls->view_date($model->inventory_transfer_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'from_type',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'from_code',
                                'label' => 'From Name',
                                'value' => Yii::$app->general->getforeignkey($model->$from_key, $from_field),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'to_type',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'to_code',
                                'label' => 'To Name',
                                'value' => Yii::$app->general->getforeignkey($model->$to_key, $to_field),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'from_code',
                                'value' => Yii::$app->general->getforeignkey($model->$from_key, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'to_code',
                                'value' => Yii::$app->general->getforeignkey($model->$to_key, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'remarks',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'union_code',
                                'label' => 'Union',
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
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
                <h4 class="theme-box-heading">Inventory Transfer Txn Details</h4>
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