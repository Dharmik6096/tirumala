<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::$app->label->title('view', 'Manual Collection Approval');
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
                                'value' => ($model->collection_type == 1) ? Yii::$app->general->getmultiforeignkey($model->dcsCode, ['unionCode'], 'union_name') : Yii::$app->general->getmultiforeignkey($model->bmcCode, ['unionCode'], 'union_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'plant_code',
                                'value' => ($model->collection_type == 1) ? Yii::$app->general->getmultiforeignkey($model->dcsCode, ['plantCode'], 'name') : Yii::$app->general->getmultiforeignkey($model->bmcCode, ['plantCode'], 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'mcc_plant_code',
                                'value' => ($model->collection_type == 1) ? Yii::$app->general->getmultiforeignkey($model->dcsCode, ['mccPlantCode'], 'name') : Yii::$app->general->getmultiforeignkey($model->bmcCode, ['tblMccPlant'], 'name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'bmc_code',
                                'value' => ($model->collection_type == 1) ? Yii::$app->general->getmultiforeignkey($model->dcsCode, ['bmcCode'], 'bmc_name') : Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'dcs_code',
                                'value' => ($model->collection_type == 1) ? Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'code',
                                'label' => Yii::t('app', 'Ref. Code'),
                                'value' => ($model->collection_type == 1) ? Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code') : Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
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
                                'value' => isset($model->collection_type) ? Yii::$app->dropdown->getRecords('approval_collection_type')['data'][$model->collection_type] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'is_approve',
                                'value' => ($model->is_approve == 1) ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
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
