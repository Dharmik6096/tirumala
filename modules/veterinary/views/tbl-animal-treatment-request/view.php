<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Animal Treatment Requests');
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
                            'label' => ($model->member_type == 'NonMember') ? Yii::t('app', 'Member') . ' Code' : Yii::t('app', 'Member') . ' Code Ex',
                            'value' => ($model->member_type == 'NonMember') ? $model->member_code : Yii::$app->general->getforeignkey($model->memberCode, 'ex_member_code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'member_name',
                            'label' => Yii::t('app', 'Member') . ' Name',
                            'value' => ($model->member_type == 'NonMember') ? $model->member_name : Yii::$app->general->getforeignkey($model->memberCode, 'member_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'mobile_number',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'address',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'member_type',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'case_type_id',
                            'value' => Yii::$app->general->getforeignkey($model->caseTypeId, 'case_type_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                         [
                            'attribute' => 'member_animal_tag_id',
                            'value' => Yii::$app->general->getforeignkey($model->memberAnimalTagId, 'tag_no'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'disease_id',
                            'value' => Yii::$app->general->getforeignkey($model->diseaseId, 'disease_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'animal_type_id',
                            'value' => Yii::$app->general->getforeignkey($model->animalTypeId, 'animal_type_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'gender_id',
                            'value' => Yii::$app->general->getforeignkey($model->genderId, 'gender'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [                        
                        [
                            'attribute' => 'breed_id',
                            'value' => Yii::$app->general->getforeignkey($model->breedId, 'breed_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'tran_datetime',
                            'value' => Yii::$app->controls->view_date($model->tran_datetime),
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
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Diagnosis Details</h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_diagnosis_details_list', [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                    ])
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Treatment Details</h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_treatment_details_list', [
                        'dataProvider' => $treatmentDataProvider,
                        'searchModel' => $treatmentSearchModel,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>