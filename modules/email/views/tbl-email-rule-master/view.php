<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Email Rule Master');
$this->params['menu'][] = Yii::$app->controls->add('Email Rule Master');
if ($model->is_active == 1) {
    $this->params['menu'][] = Yii::$app->controls->update($model->email_rule_master_id);
}
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
                            'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'rule_id',
                            'value' => isset($model->emailProcess) ? $model->emailProcess->process_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'frequency',
                            'value' => Yii::$app->dropdown->getRecords('frequency_data')['data'][$model->frequency],
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'interval',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'email',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'no_of_email',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'mobile',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'is_active',
                            'label' => 'Status',
                            'format' => 'html',
                            'value' => GeneralFunctions::getRecordStatus($model->is_active),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'email_subject',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'email_body',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'message',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
            ];
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'mode' => 'view',
                'bordered' => true,
                'striped' => false,
                'responsive' => true,
                'hAlign' => 'left',
                'vAlign' => 'top',
                'deleteOptions' => [ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
    </div>
</div>