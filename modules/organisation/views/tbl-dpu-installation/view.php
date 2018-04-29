<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use app\modules\organisation\models\TblDcs;

$this->title = Yii::$app->label->title('view', 'Dpu Installation');
$query=  TblDcs::find()->select(['is_active'])->where(['dcs_code'=>  $model->dcs_code])->one();
if($query['is_active'] == 1) $this->params['menu'][] = Yii::$app->controls->update($model->inst_code);
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
                            'value' => !empty($model->dcsCode->unionCode) ? $model->unionCode->union_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'dcs_code',
                            'value' => !empty($model->dcsCode) ? $model->dcsCode->dcs_name : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'inst_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'inst_date',
                            'format' => 'html',
                            'value' => Yii::$app->controls->view_date($model->inst_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'inst_by',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'simcard_company',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'dpu_sim_mobile',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'soc_secretary',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'secretary_mobile',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'attachment',
                            'value' => Yii::$app->controls->view_attachment($model,$model->attachment,'dpu_docs_path'),
                            'format' => 'raw',
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
                            'attribute' => 'is_active',
                            'label' => 'Status',
                            'format' => 'html',
                            'value' => GeneralFunctions::getRecordStatus($model->is_active),
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
                'deleteOptions' => [ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
    </div>
</div>