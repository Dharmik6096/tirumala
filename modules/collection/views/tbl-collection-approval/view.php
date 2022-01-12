<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::$app->label->title('view', 'Collection Approve');
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
                                'attribute' => 'date',
                                'format' => 'html',
                                'value' => Yii::$app->controls->view_date($model->date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'shift_code',
                                'value' => isset($model->shiftCode) ? $model->shiftCode->shift : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'collection_type',
                                'value' => isset($model->collection_type) ? Yii::$app->dropdown->getRecords('milk_collection_type')['data'][$model->collection_type] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'is_approve',
                                'value' => isset($model->is_approve) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_approve] : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'requested_by',
                                'value' => isset($model->userAndroidCode) ? $model->userAndroidCode->name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'approved_by',
                                'value' => isset($model->userCode) ? $model->userCode->name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'approve_date',
                                'format' => 'html',
                                'value' => Yii::$app->controls->view_date($model->approve_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'allow_till_date',
                                'format' => 'html',
                                'value' => Yii::$app->controls->view_date($model->allow_till_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'valid_hours',
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


        <?php if ($type == 'approve') { ?>
            <?php
            $form = ActiveForm::begin([
                        'options' => [],
                        'validateOnBlur' => FALSE,
                        'validateOnChange' => FALSE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
            ]);
            ?>
            <?php echo $form->errorSummary($model); ?>

            <div class="row">
                <div class="col-sm-2 number-validate"> 
                    <?= $form->field($model, 'valid_hours')->textInput() ?>    </div>
                <div class="clearfix"></div>
                <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Approve'), ['class' => 'btn btn-danger']) ?>
                        <?= Yii::$app->controls->cancel($model); ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
<?php } ?>
