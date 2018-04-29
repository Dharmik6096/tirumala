<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblFormulaMaster */

$this->title = Yii::t('app', Yii::$app->label->title('view', 'Formula'));
?>
<div class="panel panel-main">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'formula_code',
                            'valueColOptions' => ['style' => 'width:80%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'formula_description',
                            'valueColOptions' => ['style' => 'width:80%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'formula',
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
    <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>          
