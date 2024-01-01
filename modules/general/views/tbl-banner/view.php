<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlant */

$this->title = Yii::$app->label->title('view', 'Banner');
?>
<div class="panel panel-default panel-grid panel-main tbl-complain-view hide-grid-settings">
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
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'banner_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'from_date',
                                'value' => Yii::$app->controls->view_date($model->from_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'to_date',
                                'value' => Yii::$app->controls->view_date($model->to_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'title',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'description',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'tap_operation',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'tap_event',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'seq_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'banner_for',
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
                    'deleteOptions' => [// your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>

        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0">Banner Applicability</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('_banner_applicability', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0">Banner Attachment</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('_attachment_list', [
                    'banner_attachment' => $banner_attachment,
                    'attachmentDataProvider' => $attachmentDataProvider
                ])
                ?>
            </div>
        </div>
    </div>
</div>

