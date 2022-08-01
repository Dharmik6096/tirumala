<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use kartik\grid\GridView;

$this->title = Yii::$app->label->title('view', 'Integration Status');
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                // DetailView Attributes Configuration
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'mcc_plant_code',
                                'label' => Yii::t('app', 'MCC'),
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'date_time_of_collection',
                                'label' => 'Collection Date',
                                'value' => Yii::$app->controls->view_date($model->date_time_of_collection),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'shift_code',
                                'label' => Yii::t('app', 'Shift'),
                                'value' => Yii::$app->general->getforeignkey($model->shiftCode, 'shift'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'qty',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'avg_fat',
                                'label' => Yii::t('app', 'Avg FAT'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'avg_snf',
                                'label' => Yii::t('app', 'Avg SNF'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'amount',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'data_lock',
                                'label' => Yii::t('app', 'DATA Status'),
                                'value' => $model->data_lock == 1 ? 'LOCK' : 'UN-LOCK',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'x_col1',
                                'label' => Yii::t('app', 'Member Qty'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'x_col2',
                                'label' => Yii::t('app', 'Member Avg FAT'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'x_col3',
                                'label' => Yii::t('app', 'Member Avg SNF'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'x_col4',
                                'label' => Yii::t('app', 'Member Amount'),
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
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'History') ?></h4>
            </div>
            <div class="form-grid">
                <?php
                $attribute = [
                    ['attribute' => 'qty', 'filter' => FALSE],
                    ['attribute' => 'avg_fat', 'filter' => FALSE],
                    ['attribute' => 'avg_snf', 'filter' => FALSE],
                    ['attribute' => 'amount', 'filter' => FALSE],
                    ['attribute' => 'x_col1', 'filter' => FALSE, 'label' => Yii::t('app', 'BMC Qty')],
                    ['attribute' => 'x_col2', 'filter' => FALSE, 'label' => Yii::t('app', 'BMC Avg FAT')],
                    ['attribute' => 'x_col3', 'filter' => FALSE, 'label' => Yii::t('app', 'BMC Avg SNF')],
                    ['attribute' => 'x_col4', 'filter' => FALSE, 'label' => Yii::t('app', 'BMC Amount')],
                    ['label' => Yii::t('app', 'DATA Status'), 'attribute' => 'data_lock', 'value' => function($model) {
                            return $model->data_lock == 1 ? 'LOCK' : 'UN-LOCK';
                        }, 'filter' => FALSE],
                    ['attribute' => 'history_created_at', 'value' => function($model) {
                            return Yii::$app->controls->view_datetime($model->history_created_at);
                        }, 'filter' => FALSE],
                    ['attribute' => 'history_created_by', 'value' => function($model) {
                            return Yii::$app->general->getforeignkey($model->userCode, 'name');
                        }, 'filter' => FALSE],
                ];

                $grid_option = [
                    'id' => 'shift-lock-status-history-list',
                    'attributes' => $attribute,
                    'active_column' => false,
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                ?>
            </div>       
        </div> 
    </div>
</div>