<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use yii\web\View;

$this->title = Yii::$app->label->title('view', 'Feedback Master');
?>
<div class="panel panel-default panel-grid panel-main p-0">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
        <div class="refreshBtn" id="refreshBtn"><i class="glyphicon glyphicon-repeat"></i></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'eipl_app_feedback_master_code',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'name',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'mcc_plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->mccCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'dcs_code',
                            'value' => (strtolower($model->user_type) == 'vsp' || strtolower($model->user_type) == 'farmer') ? Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') : '',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'eipl_app_feedback_master_code',
                            'value' => Yii::$app->general->getforeignkey($model->eiplAppFeedbackItemCode, 'feedback_item_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'feedback_message',
                            'valueColOptions' => ['style' => 'width:30%', 'class' => 'text-wrap'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'feedback_message_datetime',
                            'value' => Yii::$app->controls->view_date($model->feedback_message_datetime),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'user_type',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'feedback_status',
                            'value' => Yii::$app->dropdown->getRecords('feedback_status')['data'][$model->feedback_status],
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
            <?=
            $this->render('message_thread', [
                'model' => $model,
                'feedbackMasterTxn' => $feedbackMasterTxn
            ])
            ?>
    </div>
</div>
<?php
$script = "
    $(document).ready(function(){
        $('#refreshBtn').click(function(){
            location.reload(true);
        });
    });
    ";
$this->registerJs($script, View::POS_END, 'tbl-eipl-app-feedback-master-view');
?>
