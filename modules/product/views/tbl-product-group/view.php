<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Product Group');
$this->params['menu'][] = Yii::$app->controls->update($model->product_group_code);
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
                            'attribute' => 'product_group_code',
                            'valueColOptions' => ['style' => 'width:80%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'product_group_name',
                            'valueColOptions' => ['style' => 'width:80%'],
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
                            'valueColOptions' => ['style' => 'width:80%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'local_name',
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
                'deleteOptions' => [ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
    </div>
</div>