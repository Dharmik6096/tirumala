<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Mrg Meeting Master');
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
                            'attribute' => 'm_from_date',
                            'value' => Yii::$app->controls->view_date($model->m_from_date),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'm_to_date',
                            'value' => Yii::$app->controls->view_time($model->m_to_date),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'MRG_date',
                            'value' => Yii::$app->controls->view_date($model->MRG_date),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'from_time',
                            'value' => Yii::$app->controls->view_time($model->from_time),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'to_time',
                            'value' => Yii::$app->controls->view_time($model->to_time),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'attandance_count',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'pib_office_code',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'pib_office_code',
                            'value' => Yii::$app->general->getforeignkey($model->pibOfficeCode, 'name'),
                            'label' => Yii::t('app', 'Pib Office Name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'pib_office_code',
                            'value' => Yii::$app->general->getforeignkey($model->pibOfficeCode, 'employee_id'),
                            'label' => Yii::t('app', 'Pib Office Employee ID'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'area_office_code',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'area_office_code',
                            'value' => Yii::$app->general->getforeignkey($model->areaOfficeCode, 'name'),
                            'label' => Yii::t('app', 'Area Office Name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'area_office_code',
                            'value' => Yii::$app->general->getforeignkey($model->areaOfficeCode, 'employee_id'),
                            'label' => Yii::t('app', 'Area Office Employee ID'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'status',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'remarks',
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
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#mapping_grid" aria-expanded="true" aria-controls="mapping_grid">Org Mapping</h4>
            </div>
            <div class="col-sm-12 collapse in" id="mapping_grid">
                <?=
                $this->render('_mapping_grid', [
                    'mappingDataProvider' => $mappingDataProvider,
                    'mappingSearchModel' => $mappingSearchModel,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#attendance_grid" aria-expanded="true" aria-controls="attendance_grid">
                    Attendance
                </h4>
            </div>
            <div class="col-sm-12 collapse in" id="attendance_grid">
                <?=
                $this->render('_attendance_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#statistics_grid" aria-expanded="true" aria-controls="statistics_grid">
                Statistics
            </h4>
            </div>
            <div class="col-sm-12 collapse in" id="statistics_grid">
                <?=
                $this->render('_statistics_grid', [
                    'dataProvider' => $statisticsDataProvider,
                    'searchModel' => $statisticsSearchModel,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#mom_grid" aria-expanded="true" aria-controls="mom_grid">Mom</h4>
            </div>
            <div class="col-sm-12 collapse in" id="mom_grid">
                <?=
                $this->render('_mom_grid', [
                    'momDataProvider' => $momDataProvider,
                    'momSearchModel' => $momSearchModel,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#feedback_grid" aria-expanded="true" aria-controls="feedback_grid">Feedback</h4>
            </div>
            <div class="col-sm-12 collapse in" id="feedback_grid">
                <?=
                $this->render('_feedback_grid', [
                    'feedbackDataProvider' => $feedbackDataProvider,
                    'feedbackSearchModel' => $feedbackSearchModel,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#info_sharing_grid" aria-expanded="true" aria-controls="info_sharing_grid">Info Sharing</h4>
            </div>
            <div class="col-sm-12 collapse in" id="info_sharing_grid">
                <?=
                $this->render('_info_sharing_grid', [
                    'sharingDataProvider' => $sharingDataProvider,
                    'sharingSearchModel' => $sharingSearchModel,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#previous_grid" aria-expanded="true" aria-controls="previous_grid">Previous Action</h4>
            </div>
            <div class="col-sm-12 collapse in" id="previous_grid">
                <?=
                $this->render('_previous_action', [
                    'previousDataProvider' => $previousDataProvider,
                    'previousSearchModel' => $previousSearchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>
