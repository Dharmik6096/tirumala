<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', ' Member Animal Tag Details');
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
                            'attribute' => 'dcs_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'dcs_code',
                            'label' => Yii::t('app', 'DCS') . ' Ref Code',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'dcs_code',
                            'label' => Yii::t('app', 'DCS') . ' Name',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'member_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'member_code',
                            'label' => Yii::t('app', 'Member') . ' Code Ex',
                            'value' => Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'member_code',
                            'label' => Yii::t('app', 'Member') . ' Name',
                            'value' => Yii::$app->general->getforeignkey($model->memberCode, 'member_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'mobile_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'email',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'tag_no',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'animal_type_id',
                            'value' => Yii::$app->general->getforeignkey($model->animalTypeId, 'animal_type_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'gender_id',
                            'value' => Yii::$app->general->getforeignkey($model->genderId, 'gender'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'breed_id',
                            'value' => Yii::$app->general->getforeignkey($model->breedId, 'breed_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'year',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'month',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'no_of_calving',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'last_date_of_calving',
                            'value' => Yii::$app->controls->view_date($model->last_date_of_calving),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'pregnancy_status',
                            'value' => Yii::$app->general->getStaticDropdownVal('pregnancy_status', $model, 'pregnancy_status'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'pregnancy_month',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'pregnancy_month_on_date',
                            'value' => Yii::$app->controls->view_date($model->pregnancy_month_on_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'milking_status',
                            'value' => Yii::$app->general->getStaticDropdownVal('milking_status', $model, 'milking_status'),
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
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
        <?=
        $this->render('_document_grid', [
            'attachmentDataProvider' => $dataProvider,
            'attachment' => $attachment,
        ]);
        ?>
    </div>
</div>