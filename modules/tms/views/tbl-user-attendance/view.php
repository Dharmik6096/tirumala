<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Attendance');
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
                            'attribute' => 'attendance_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'user_code',
                            'value' => Yii::$app->general->getforeignkey($model->userCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'attendance_date',
                            'value' => Yii::$app->controls->view_date($model->attendance_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'created_at',
                            'value' => Yii::$app->controls->view_date($model->created_at),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'in_time',
                            'value' => Yii::$app->controls->view_time($model->in_time),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'out_time',
                            'value' => Yii::$app->controls->view_time($model->out_time),
                            'valueColOptions' => ['style' => 'width:30%']
                        ]
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'mcc_plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'dcs_code',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'mobile_no',
                            'value' => Yii::$app->general->getforeignkey($model->userCode, 'mobile_no'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'Attendance_hours',
                            'value' => Yii::$app->controls->timeDifference($model->in_time, $model->out_time),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'in_lat_long',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'out_lat_long',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'in_desc',
                            'format' => 'raw',
                            $in_lat_long = Yii::$app->general->getforeignkey($model->userAttendance, 'in_lat_long'),
                            'value' => Yii::$app->controls->openInGoogleMaps($model->in_desc, $in_lat_long),
                            'valueColOptions' => ['style' => 'width:100%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'out_desc',
                            'format' => 'raw',
                            $out_lat_long = Yii::$app->general->getforeignkey($model->userAttendance, 'out_lat_long'),
                            'value' => Yii::$app->controls->openInGoogleMaps($model->out_desc, $out_lat_long),
                            'valueColOptions' => ['style' => 'width:100%']
                        ]
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
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0">User Attachment</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('_attachment_list', [
                    'model' => $model,
                    'user_attachment' => $user_attachment,
                    'attachmentDataProvider' => $attachmentDataProvider,
                ])
                ?>
            </div>
        </div>
    </div>
</div>