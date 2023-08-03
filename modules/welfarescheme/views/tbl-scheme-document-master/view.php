<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Document Master');
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
                            ['attribute' => 'doc_group',
                            'value' => isset($model->doc_group) ? Yii::$app->dropdown->getRecords('doc_group')['data'][$model->doc_group] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'doc_name',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                            [
                            'attribute' => 'doc_ext',
                            'value' => !empty($model->doc_ext) && !empty(Yii::$app->dropdown->getRecords('doc_ext')['data'][$model->doc_ext]) ? Yii::$app->dropdown->getRecords('doc_ext')['data'][$model->doc_ext] : (!empty($model->doc_ext) ? $model->doc_ext : ''),
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
</div>