<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->general->getforeignkey($model->formTypeCode, 'form_name');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                $rel = Yii::$app->general->getDestRelation($model->module_type);
                $att = strtolower($model->module_type) == 'bmc' ? 'bmc_name' : (strtolower($model->module_type) == 'dcs' ? 'dcs_name' : 'name');
                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'module_type',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'module_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'module_code',
                                'label' => Yii::t('app', 'Ref.Code'),
                                'value' => Yii::$app->general->getforeignkey($model->{$rel}, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'module_code',
                                'label' => Yii::t('app', 'Name'),
                                'value' => Yii::$app->general->getforeignkey($model->{$rel}, $att),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'contact_person',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'contact_person_mobile_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'user_code',
                                'value' => Yii::$app->general->getforeignkey($model->userCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'activity_datetime',
                                'value' => Yii::$app->controls->view_datetime($model->activity_datetime),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'remarks',
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
                    'deleteOptions' => [
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Form Detail') ?></h4>
            </div>
            <div class="form-grid hide-grid-settings panel_clear_both">
                <?php
                $grid_option = [
                    'id' => 'task-activity-list',
                    'attributes' => [
                            ['attribute' => 'question',],
                            ['attribute' => 'answer',],
                    ],
                    'active_column' => FALSE,
                ];
                Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], FALSE);
                ?>
            </div>
        </div> 
    </div>
</div>