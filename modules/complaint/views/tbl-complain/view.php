<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlant */

$this->title = 'Complaint Detail (' . $model->complain_code . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Complains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                                'attribute' => 'plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'mcc_plant_code',
                                'label' => Yii::t('app', 'MCC').' Ref Code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'dcs_code',
                                'label' => Yii::t('app', 'DCS').' Ref Code',
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'mcc_plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
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
                                'attribute' => 'contact_person',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'mobile_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'complain_type_code',
                                'value' => isset($model->complainFors) ? $model->complainFors->complain_type : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'complain_datetime',
                                'value' => Yii::$app->controls->view_date($model->complain_datetime),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'affects_data',
                                'value' => $model->affects_data == 1 ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'physical_damage',
                                'value' => $model->physical_damage == 1 ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'asset_code',
                                'value' => isset($model->asset) ? $model->asset->asset_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'complain_status',
                                'value' => function($model) {
                                    return !empty($model->complain_status) ? Yii::$app->dropdown->getRecords('complain_status')['data'][$model->complain_status] : '';
                                },
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'serial_number',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'new_serial_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'remarks',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'user_code',
                                'value' => !empty($model->contactDetailsCodes) ? $model->contactDetailsCodes->name . '(' . $model->contactDetailsCodes->mobile_no . ')' : '',
                                'format' => 'raw',
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
                <h4 class="theme-box-heading mt_0">Complain Activity</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('_complaint_activity', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0">Complain Attachment</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('_attachment_list', [
                    'complain_attachment' => $complain_attachment,
                    'attachmentDataProvider' => $attachmentDataProvider
                ])
                ?>
            </div>
        </div>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0">Complain Escalation Transaction Detail</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('_escalation_txn_grid', [
                    'complain_escalation_txn' => $complain_escalation_txn,
                    'escalationTxnDataProvider' => $escalationTxnDataProvider
                ])
                ?>
            </div>
        </div>
    </div>
</div>