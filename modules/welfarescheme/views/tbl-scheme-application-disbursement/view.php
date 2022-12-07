<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Scheme Application Disbursement');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
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
                            'attribute' => 'scheme_id',
                            'value' => !empty($model->schemeId) ? $model->schemeId->scheme_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'application_id',
                          //  'value' => !empty($model->ApplicationId) ? $model->ApplicationId->member_code : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'disburse_date',
                            'value' => Yii::$app->controls->view_date($model->disburse_date),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'disburse_value',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                            [
                            'attribute' => 'payment_mode',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'bank_name',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                            [
                            'attribute' => 'branch_name',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'party_name',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                            [
                            'attribute' => 'party_relation',
                           // 'value' => !empty($model->relationship) ? $model->relationship->relationship : '',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'payment_ref_id',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                            [
                            'attribute' => 'payment_detail',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'remarks',
                            'valueColOptions' => ['style' => 'width:80%'],
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
</div>
