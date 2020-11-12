<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlant */

$this->title = 'Complaint Detail (' . $model->complaint_code . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Complaints'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$status = $model->getComplaintStatus($model->complaint_code);
if (!isset($status))
    $this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-pencil"></i> Edit'), Url::to(['tbl-complaint-activity/create', 'id' => $model->complaint_code]), ['class' => 'btn btn-danger btn-block apply-shortcut', 'shortcut_key' => 'ctrl+alt+e']);
?>
<div class="panel panel-default panel-grid panel-main tbl-complaint-view">
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
                                'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'dcs_code',
                                'value' => isset($model->dcsCode) ? $model->dcsCode->dcs_name : '',
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
                                'attribute' => 'complaint_type',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'date',
                                'value' => Yii::$app->controls->view_date($model->date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'status',
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
                                'attribute' => 'attachment',
                                'value' => Yii::$app->controls->view_attachment($model, $model->attachment, 'complaint_dir_path'),
                                'format' => 'raw',
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
                    'deleteOptions' => [ // your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>

        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Complaint Activity</h4>
            </div>
        <div class="clearfix"></div>
        <?=
        $this->render('../../../complaint/views/tbl-complaint-activity/index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
        </div>
    </div>
</div>