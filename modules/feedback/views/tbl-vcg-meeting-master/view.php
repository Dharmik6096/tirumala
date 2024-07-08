<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Vcg Meeting Master');
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
                            'attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'bmc_code',
                            'label' => Yii::t('app', 'BMC Name'),
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'dcs_code',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'dcs_code',
                            'label' => Yii::t('app', 'DCS Name'),
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'VCG_date',
                            'value' => Yii::$app->controls->view_date($model->VCG_date),
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
                            'attribute' => 'route_supervisor_code',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'route_supervisor_code',
                            'label' => Yii::t('app', 'Route Supervisor Name'),
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
                            'attribute' => 'status',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'remarks',
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
            <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#member_grid" aria-expanded="true" aria-controls="member_grid">
                    Non Pouring Member
                </h4>
            </div>
            <div class="col-sm-12 collapse in" id="member_grid">
                <?=
                $this->render('_non_pouring_member_grid', [
                    'dataProvider' => $memberDataProvider,
                    'searchModel' => $memberSearchModel,
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
