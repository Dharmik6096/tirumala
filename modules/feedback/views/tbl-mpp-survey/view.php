<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'MPP Survey');
?>
<div class="panel panel-default panel-grid hide-grid-settings">
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
                            'attribute' => 'mcc_plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'mcc_plant_code',
                            'label' => Yii::t('app', 'MCC Name'),
                            'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'mpp_survey_code',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'survey_person_code',
                            'value' => Yii::$app->general->getforeignkey($model->surveyPersonCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'survey_date',
                            'value' => Yii::$app->controls->view_date($model->survey_date),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'mpp_name',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'state_code',
                            'value' => Yii::$app->general->getforeignkey($model->stateCode, 'state_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'district_code',
                            'value' => Yii::$app->general->getforeignkey($model->districtCode, 'district_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'sub_district_code',
                            'value' => Yii::$app->general->getforeignkey($model->subDistrictCode, 'sub_district_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'village_code',
                            'value' => Yii::$app->general->getforeignkey($model->villageCode, 'village_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'hamlet_code',
                            'value' => Yii::$app->general->getforeignkey($model->hamletCode, 'hamlet_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'pincode',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'no_of_family_gen',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'no_of_family_obc',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'no_of_family_st',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'no_of_family_sc',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'no_of_family_other',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'no_of_family_total',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'milch_animal_cow_cnt',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'milch_animal_buff_cnt',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'milch_animal_country_cow_cnt',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'milch_animal_cnt_total',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'non_milch_animal_cow_cnt',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'non_milch_animal_buff_cnt',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'non_milch_animal_country_cow_cnt',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'non_milch_animal_cnt_total',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'cow_milk_volume',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'buff_milk_volume',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'total_milk_volume',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'nos_of_pouring_members',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'per_day_milk_sales_volume',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'expected_pourer_count',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'expected_milk_volume',
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
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#competitor_grid" aria-expanded="true" aria-controls="competitor_grid">
                    Competitors
                </h4>
            </div>
            <div class="col-sm-12 collapse in" id="competitor_grid">
                <?=
                $this->render('_competitor_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#sahayak_grid" aria-expanded="true" aria-controls="competitor_grid">
                    Probable Sahayak
                </h4>
            </div>
            <div class="col-sm-12 collapse in" id="sahayak_grid">
                <?=
                $this->render('_probable_sahayak_grid', [
                    'dataProvider' => $sahayakDataProvider,
                    'searchModel' => $sahayakSearchModel,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#member_grid" aria-expanded="true" aria-controls="competitor_grid">
                    Probable Member
                </h4>
            </div>
            <div class="col-sm-12 collapse in" id="member_grid">
                <?=
                $this->render('_probable_member_grid', [
                    'dataProvider' => $memberDataProvider,
                    'searchModel' => $memberSearchModel,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#document_grid" aria-expanded="true" aria-controls="competitor_grid">Document Upload</h4>
            </div>
            <div class="col-sm-12 collapse in" id="document_grid">
                <?=
                $this->render('_document_grid', [
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment,
                ])
                ?>
            </div>
        </div>
    </div>
</div>